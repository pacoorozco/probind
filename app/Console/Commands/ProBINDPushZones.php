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

use App\Server;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP;
use Registry;

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

    /**
     * The local Storage path to be used
     *
     * @var string
     */
    protected $localStoragePath;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->localStoragePath = storage_path('probind');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        // Prepare local storage for file's creation
        Storage::deleteDirectory($this->localStoragePath);

        // Generate a file containing zone's name that has been deleted
        $deletedZones = Zone::onlyTrashed()->get();
        $content = $this->generateDeletedZonesContent($deletedZones);
        Storage::put($this->localStoragePath . '/configuration/deadlist', $content, 'private');

        // Generate one file for zone with its zone definition
        $zonesToUpdate = Zone::withPendingChanges()->get();
        foreach ($zonesToUpdate as $zone) {
            $this->generateZoneFileForZone($zone);
        }

        // Now push files to servers using SFTP
        $error = $this->handleAllServers();

        if ( ! $error) {
            // Clear pending changes on zones and clear deleted ones
            foreach ($zonesToUpdate as $zone) {
                $zone->setPendingChanges(false);
            }

            foreach ($deletedZones as $zone) {
                $zone->forceDelete();
            }
        }

        return $error;
    }

    /**
     * Returns the content of Deleted Zones File
     *
     * @param array $deletedZones
     * @return string
     */
    public function generateDeletedZonesContent($deletedZones)
    {
        $content = [];
        foreach ($deletedZones as $zone) {
            $content[] = sprintf("%s\n", $zone->domain);
        }

        return join($content);
    }

    /**
     * Creates a file with the zone definitions
     *
     * @param Zone $zone
     * @return bool
     */
    public function generateZoneFileForZone(Zone $zone)
    {
        $path = $this->localStoragePath . '/primary/' . $zone->domain;

        // Get default settings, we will use to render view
        $defaults = Registry::all();

        // Get all Name Servers that had to be on NS records
        $nameServers = Server::where('ns_record', true)
            ->orderBy('type')
            ->get();

        // Create new Serial for this zone
        $zone->setSerialNumber();

        // Get all records
        $records = $zone->records()
            ->orderBy('type')
            ->get();

        // Put a header on generated file
        $header = sprintf(";\n; This file has been automatically generated using ProBIND v3 on %s.\n;",
            Carbon::now());
        Storage::put($path, $header, 'private');

        // Create file content with a blade view
        $contents = view('templates.zone')
            ->with('defaults', $defaults)
            ->with('zone', $zone)
            ->with('servers', $nameServers)
            ->with('records', $records);

        return Storage::append($path, $contents, 'private');
    }

    /**
     * Push files to all Servers
     *
     * @return bool
     */
    public function handleAllServers()
    {
        // Get servers with push updates capability
        $servers = Server::withPushCapability()->get();

        $pushedWithoutErrors = ! $servers->isEmpty();
        foreach ($servers as $server) {
            $pushedWithoutErrors &= $this->handleServer($server);
        }

        return $pushedWithoutErrors;
    }

    /**
     * Handle this command only for one Server
     *
     * @param Server $server
     * @return bool
     */
    public function handleServer(Server $server)
    {
        $this->generateConfigFileForServer($server);

        // Create an array with files that need to be pushed to remote server
        $filesToPush = [
            [
                'local'  => $this->localStoragePath . '/configuration/' . $server->hostname . '.conf',
                'remote' => Registry::get('ssh_default_remote_path') . '/configuration/named.conf',
            ],
            [
                'local'  => $this->localStoragePath . '/configuration/deadlist',
                'remote' => Registry::get('ssh_default_remote_path') . '/configuration/deadlist',
            ]
        ];

        if ($server->type == 'master') {
            $localFiles = Storage::files($this->localStoragePath . '/primary/');
            foreach ($localFiles as $file) {
                $filename = basename($file);
                $filesToPush[] = [
                    'local'  => $file,
                    'remote' => Registry::get('ssh_default_remote_path') . '/primary/' . $filename
                ];
            }
        }

        return $this->pushFilesToServer($server, $filesToPush);
    }

    /**
     * Create a file with DNS server configuration
     *
     * @param Server $server
     * @return bool
     */
    public function generateConfigFileForServer(Server $server)
    {
        $path = $this->localStoragePath . '/configuration/' . $server->hostname . '.conf';

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
     * Returns the template for rendering configuration file
     *
     * @param Server $server
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
     * Push files to a Master server using SFTP
     *
     * @param Server $server
     * @param array $filesToPush
     * @return bool
     */
    public function pushFilesToServer(Server $server, $filesToPush)
    {
        // Get RSA private key in order to connect to servers
        $privateSSHKey = new RSA();
        $privateSSHKey->loadKey(Registry::get('ssh_default_key'));

        try {
            $sftp = new SFTP($server->hostname, Registry::get('ssh_default_port'));
        } catch (\Exception $e) {
            $this->error('Can\'t connect to ' . $server->hostname . ': ' . $e->getMessage());

            return false;
        }

        if ( ! $sftp->login(Registry::get('ssh_default_user'), $privateSSHKey)) {
            $this->error('Invalid SSH credentials for ' . $server->hostname);

            return false;
        }

        $this->info('Connected successfully to ' . $server->hostname);

        // Create Remote Folders, last argument is to be recursive
        $sftp->mkdir(Registry::get('ssh_default_remote_path') . '/configuration', -1, true);
        $sftp->mkdir(Registry::get('ssh_default_remote_path') . '/primary', -1, true);

        $totalFiles = count($filesToPush);
        $pushedFiles = 0;
        foreach ($filesToPush as $file) {
            $contents = Storage::get($file['local']);

            if ( ! $sftp->put($file['remote'], $contents)) {
                $this->error('File ' . $file['local'] . ' can\'t be uploaded to ' . $server->hostname);
                continue;
            }
            $pushedFiles++;
        }

        $this->info('Has been pushed ' . $pushedFiles . ' of ' . $totalFiles . ' files to ' . $server->hostname);
        $sftp->disconnect();

        // Return true if all files has been pushed
        return ($totalFiles == $pushedFiles);
    }
}
