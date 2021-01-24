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
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Console\Commands;

use App\Helpers\SFTP\SFTP;
use App\Server;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use phpseclib\Crypt\RSA;

/**
 * Class ProBINDPushZones.
 *
 * @codeCoverageIgnore
 */
class ProBINDPushZones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'probind:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and push zone files to DNS servers';

    const BASEDIR = 'probind';
    const CONFIG_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'configuration';
    const ZONE_BASEDIR = self::BASEDIR . DIRECTORY_SEPARATOR . 'primary';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        // Prepare local storage for file's creation
        Storage::deleteDirectory(self::BASEDIR);

        // Generate a file containing zone's name that has been deleted
        $deletedZones = Zone::onlyTrashed()
            ->get();
        $content = join("\n",
            $deletedZones
                ->pluck('domain')
                ->all()
        );
        $path = self::CONFIG_BASEDIR . DIRECTORY_SEPARATOR . 'deadlist';
        Storage::put($path, $content, 'private');

        // Generate one file for zone with its zone definition
        $zonesToUpdate = Zone::withPendingChanges()
            ->get();
        foreach ($zonesToUpdate as $zone) {
            $this->generateZoneFileForZone($zone);
        }

        // Now push files to servers using SFTP
        if (false === $this->handleAllServers()) {
            $this->error('Push updates completed with errors');

            return false;
        }

        // Clear pending changes on zones and clear deleted ones
        foreach ($zonesToUpdate as $zone) {
            $zone->setPendingChanges(false);
        }

        foreach ($deletedZones as $zone) {
            $zone->forceDelete();
        }

        $this->info('Push updates completed successfully.');

        return true;
    }

    /**
     * Returns the content of Deleted Zones File.
     *
     * @return string
     */
    public function generateDeletedZonesContent($deletedZones): string
    {
        $content = [];
        foreach ($deletedZones as $zone) {
            $content[] = sprintf("%s\n", $zone->domain);
        }

        return join('', $content);
    }

    /**
     * Creates a file with the zone definitions.
     *
     * @param  Zone  $zone
     *
     * @return bool
     */
    public function generateZoneFileForZone(Zone $zone)
    {
        // Get default settings, we will use to render view
        $defaults = setting()->all();

        // Get all Name Servers that had to be on NS records
        $nameServers = Server::where('ns_record', true)
            ->orderBy('type')
            ->get();

        // Create new Serial for this zone
        $zone->getNewSerialNumber();

        // Get all records
        $records = $zone->records()
            ->orderBy('type')
            ->get();

        // Create file content with a blade view
        $contents = view('templates.zone')
            ->with('date', Carbon::now())
            ->with('defaults', $defaults)
            ->with('zone', $zone)
            ->with('servers', $nameServers)
            ->with('records', $records);

        $path = self::ZONE_BASEDIR . DIRECTORY_SEPARATOR . $zone->domain;

        return Storage::append($path, $contents);
    }

    /**
     * Push files to all Servers.
     *
     * @return bool
     */
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

    /**
     * Handle this command only for one Server.
     *
     * @param  Server  $server
     *
     * @return bool
     */
    public function handleServer(Server $server)
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
     *
     * @return bool
     */
    public function generateConfigFileForServer(Server $server)
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
     *
     * @return string
     */
    public function getTemplateForConfigFile(Server $server)
    {
        $serverTemplateFileName = 'templates.config_' . $server->hostname;
        $templateFile = View::exists($serverTemplateFileName)
            ? $serverTemplateFileName
            : 'templates.config_' . $server->type;

        return $templateFile;
    }

    /**
     * Push files to a Master server using SFTP.
     *
     * @param  Server  $server
     * @param  array  $filesToPush
     *
     * @return bool
     */
    public function pushFilesToServer(Server $server, $filesToPush): bool
    {
        try {
            $this->info('Connecting to ' . setting()->get('ssh_default_user') . '@' . $server->hostname . ' (' . setting()->get('ssh_default_port') . ')...');

            // Get RSA private key in order to connect to servers
            $privateSSHKey = new RSA();
            if (false === $privateSSHKey->loadKey(setting()->get('ssh_default_key'))) {
                $this->error('Invalid RSA private key, configure it on the Settings page.');

                return false;
            }

            $sftp = new SFTP($server->hostname, setting()->get('ssh_default_port'));
            $sftp->authWithPublicKey(setting()->get('ssh_default_user'), $privateSSHKey);
        } catch (\Throwable $e) {
            $this->error('Connection to ' . $server->hostname . ' failed: ' . $e->getMessage());

            return false;
        }

        $this->info('Connected successfully to ' . $server->hostname . '.');

        $totalFiles = count($filesToPush);
        $pushedFiles = 0;
        foreach ($filesToPush as $file) {
            $this->info('Uploading file [' . $file['local'] . ' -> ' . $file['remote'] . '].');

            if (false === $sftp->put(Storage::path($file['local']), $file['remote'])) {
                $this->error('File ' . $file['local'] . ' can\'t be uploaded to ' . $server->hostname);
                continue;
            }
            $pushedFiles++;
        }

        $this->info('It has been pushed ' . $pushedFiles . ' of ' . $totalFiles . ' files to ' . $server->hostname . '.');
        $sftp->disconnect();

        // Return true if all files has been pushed
        return $totalFiles === $pushedFiles;
    }
}
