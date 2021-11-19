<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Console\Commands;

use App\Jobs\UpdateZoneSerialName;
use App\Models\Server;
use App\Models\Zone;
use App\Services\Formatters\BINDFormatter;
use App\Services\SFTP\SFTPPusher;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use PacoOrozco\OpenSSH\PrivateKey;

class ProBINDPushZones extends Command
{
    const SUCCESS_CODE = 0;
    const ERROR_PUSHING_FILES_CODE = 1;

    const BASEDIR = 'probind';
    const CONFIG_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'configuration';
    const ZONE_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'primary';

    protected $signature = 'probind:push';
    protected $description = 'Generate and push zone files to DNS servers';

    public function handle(): int
    {
        // Prepare local storage for file's creation
        Storage::deleteDirectory(self::BASEDIR);

        // Generate a file containing zone's name that has been deleted
        $deletedZones = Zone::onlyTrashed()
            ->get();

        $this->generateDeletedZonesFile($deletedZones);

        // Generate one file for zone with its zone definition
        $zonesToUpdate = Zone::withPendingChanges()
            ->get();
        foreach ($zonesToUpdate as $zone) {
            $this->generateZoneFile($zone);
        }

        // Now push files to servers using SFTP
        if (false === $this->handleAllServers()) {
            $this->error('Push updates completed with errors');

            return self::ERROR_PUSHING_FILES_CODE;
        }

        // Clear pending changes on zones and clear deleted ones
        foreach ($zonesToUpdate as $zone) {
            $zone->setPendingChanges(false);
        }

        foreach ($deletedZones as $zone) {
            $zone->forceDelete();
        }

        $this->info('Push updates completed successfully.');

        return self::SUCCESS_CODE;
    }

    private function generateDeletedZonesFile(Collection $deletedZones): void
    {
        $content = BINDFormatter::getDeletedZonesFileContent($deletedZones);

        $path = self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . 'deadlist';
        Storage::put($path, $content, 'private');
    }

    /**
     * Creates a file with the zone definitions.
     *
     * @param  Zone  $zone
     * @return bool
     */
    public function generateZoneFile(Zone $zone): bool
    {
        UpdateZoneSerialName::dispatchSync();

        $content = BINDFormatter::getZoneFileContent($zone);

        $path = self::ZONE_BASEDIR . DIRECTORY_SEPARATOR . $zone->domain;

        return Storage::append($path, $content);
    }

    public function handleAllServers(): bool
    {
        // Get servers with push updates capability
        $servers = Server::withPushCapability()->get();

        if (true === $servers->isEmpty()) {
            // There is no server to be pushed, so everything is fine.
            return true;
        }

        $pushedWithErrors = false;
        foreach ($servers as $server) {
            if (false === $this->handleServer($server) && (false === $pushedWithErrors)) {
                $pushedWithErrors = true;
            }
        }

        return ! $pushedWithErrors;
    }

    public function handleServer(Server $server): bool
    {
        $this->generateConfigFileForServer($server);

        // Create an array with files that need to be pushed to remote server
        $filesToPush = [
            [
                'local' => self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . $server->hostname . '.conf',
                'remote' => setting()->get('ssh_default_remote_path') . '/configuration/named.conf',
            ],
            [
                'local' => self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . 'deadlist',
                'remote' => setting()->get('ssh_default_remote_path') . '/configuration/deadlist',
            ],
        ];

        if ($server->type == 'master') {
            $localFiles = Storage::files(self::ZONE_BASEDIR);
            foreach ($localFiles as $file) {
                $filename = basename($file);
                $filesToPush[] = [
                    'local' => $file,
                    'remote' => setting()->get('ssh_default_remote_path') . '/primary/' . $filename,
                ];
            }
        }

        return $this->pushFilesToServer($server, $filesToPush);
    }

    /**
     * Create a file with DNS server configuration.
     *
     * @param  Server  $server
     * @return bool
     */
    public function generateConfigFileForServer(Server $server): bool
    {
        $path = self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . $server->hostname . '.conf';

        // We allow specific templates for each server
        $templateFile = $this->getTemplateForConfigFile($server);

        // Get zones depending Server type
        $zones = ($server->type == 'master')
            ? Zone::all()
            : Zone::onlyMasterZones();

        // Only one server can be master of a zone
        $master = Server::where('type', 'master')->first();

        $contents = view($templateFile)
            ->with('server', $server)
            ->with('zones', $zones)
            ->with('master', $master);

        return Storage::put($path, $contents, 'private');
    }

    /**
     * Returns the template for rendering configuration file.
     *
     * @param  Server  $server
     * @return string
     */
    public function getTemplateForConfigFile(Server $server): string
    {
        $serverTemplateFileName = 'templates.config_' . $server->hostname;

        return View::exists($serverTemplateFileName)
            ? $serverTemplateFileName
            : 'templates.config_' . $server->type;
    }

    public function pushFilesToServer(Server $server, array $filesToPush): bool
    {
        try {
            $this->info('Connecting to ' . setting()->get('ssh_default_user') . '@' . $server->hostname . ' (' . setting()->get('ssh_default_port') . ')...');

            // Get RSA private key in order to connect to servers
            $privateKey = PrivateKey::fromString(setting()->get('ssh_default_key'));

            $pusher = new SFTPPusher(
                $server->hostname,
                setting()->get('ssh_default_port', 22)
            );
            $pusher->login(setting()->get('ssh_default_user'), $privateKey);
        } catch (\Throwable $e) {
            $this->error('Connection to ' . $server->hostname . ' failed: ' . $e->getMessage());

            return false;
        }

        $this->info('Connected successfully to ' . $server->hostname . '.');

        $totalFiles = count($filesToPush);
        $pushedFiles = 0;

        try {
            foreach ($filesToPush as $file) {
                $this->info('Uploading file [' . $file['local'] . ' -> ' . $file['remote'] . '].');
                $pusher->pushFileTo($file['local'], $file['remote']);
                $pushedFiles++;
            }
        } catch (\Throwable $e) {
            $this->error('Error uploading files to ' . $server->hostname . ' - ' . $e->getMessage());

            return 1;
        }

        $this->info('It has been pushed ' . $pushedFiles . ' of ' . $totalFiles . ' files to ' . $server->hostname . '.');
        $pusher->disconnect();
        // Return true if all files has been pushed
        return $totalFiles === $pushedFiles;
    }
}
