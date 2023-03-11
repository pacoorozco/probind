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

use App\Models\Server;
use App\Models\Zone;
use App\Services\Formatters\BINDFormatter;
use App\Services\SFTP\SFTPPusher;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PacoOrozco\OpenSSH\PrivateKey;
use Throwable;

class ProBINDPushZones extends Command
{
    const SUCCESS_CODE = 0;

    const ERROR_PUSHING_FILES_CODE = 1;

    const BASEDIR = 'probind';

    const CONFIG_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'configuration';

    const ZONE_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'primary';

    protected $signature = 'probind:push';

    protected $description = 'Generate and push zone files to DNS servers';

    private Collection $deletedZones;

    private Collection $updatedZones;

    public function handle(): int
    {
        // Prepare local storage for file's creation
        Storage::deleteDirectory(self::BASEDIR);

        $this->deletedZones = Zone::onlyTrashed()->get();
        $this->updatedZones = Zone::withPendingChanges()->get();

        $this->preProcessDeletedZones();
        $this->preProcessPendingZones();

        // Now push files to servers using SFTP
        if (false === $this->handleAllServers()) {
            $this->error('Push updates completed with errors');

            return self::ERROR_PUSHING_FILES_CODE;
        }

        $this->postProcessDeletedZones();
        $this->postProcessPendingZones();

        $this->info('Push updates completed successfully.');

        return self::SUCCESS_CODE;
    }

    private function preProcessDeletedZones(): void
    {
        $content = BINDFormatter::getDeletedZonesFileContent($this->deletedZones);
        $path = self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . 'deadlist';
        Storage::put($path, $content, 'private');
    }

    private function preProcessPendingZones(): void
    {
        foreach ($this->updatedZones as $zone) {
            $this->generateZoneFile($zone);
        }
    }

    private function generateZoneFile(Zone $zone): void
    {
        $zone->increaseSerialNumber();
        $content = BINDFormatter::getZoneFileContent($zone);
        $path = self::ZONE_BASEDIR . DIRECTORY_SEPARATOR . $zone->domain;
        Storage::append($path, $content);
    }

    private function handleAllServers(): bool
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

        return !$pushedWithErrors;
    }

    private function handleServer(Server $server): bool
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

    private function generateConfigFileForServer(Server $server): void
    {
        $path = self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . $server->hostname . '.conf';
        $contents = BINDFormatter::getConfigurationFileContent($server);
        Storage::put($path, $contents, 'private');
    }

    private function pushFilesToServer(Server $server, array $filesToPush): bool
    {
        $this->info('Connecting to ' . setting()->get('ssh_default_user') . '@' . $server->hostname . ' (' . setting()->get('ssh_default_port') . ')...');
        try {
            // Get RSA private key in order to connect to servers
            $privateKey = PrivateKey::fromString(setting()->get('ssh_default_key'));

            $pusher = new SFTPPusher(
                $server->hostname,
                setting()->get('ssh_default_port', 22)
            );
            $pusher->login(setting()->get('ssh_default_user'), $privateKey);
            $this->info('Connected successfully to ' . $server->hostname . '.');

            $totalFiles = count($filesToPush);
            $pushedFiles = 0;

            foreach ($filesToPush as $file) {
                $this->info('Uploading file [' . $file['local'] . ' -> ' . $file['remote'] . '].');
                $pusher->pushFileTo($file['local'], $file['remote']);
                $pushedFiles++;
            }

            $pusher->disconnect();
        } catch (Throwable $e) {
            $this->error('Error pushing files to ' . $server->hostname . ' - ' . $e->getMessage());

            return false;
        }

        $this->info('It has been pushed ' . $pushedFiles . ' of ' . $totalFiles . ' files to ' . $server->hostname . '.');

        // Return true if all files has been pushed
        return $totalFiles === $pushedFiles;
    }

    private function postProcessDeletedZones(): void
    {
        foreach ($this->deletedZones as $zone) {
            $zone->forceDelete();
        }
    }

    private function postProcessPendingZones(): void
    {
        foreach ($this->updatedZones as $zone) {
            $zone->setPendingChanges(false);
        }
    }
}
