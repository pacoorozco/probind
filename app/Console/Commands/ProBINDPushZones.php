<?php

namespace App\Console\Commands;

use App\Server;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP;
use Registry;
use Storage;

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
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * Folders:
     *  |
     *  |- configuration/
     *  |   |- named.conf
     *  |   |- deadlist
     *  |
     *  |- primary/
     *      |- domain.local
     *      |- another_domain.local
     *
     * @return bool
     */
    public function handle()
    {
        $localStoragePath = storage_path('probind');

        // Get servers with push updates capability
        $servers = Server::withPushCapability()
            ->orderBy('hostname')
            ->get();

        // Get zones with pending changes
        $zonesToUpdate = Zone::withPendingChanges()
            ->orderBy('domain')
            ->get();

        // Get deleted zones
        $deletedZones = Zone::onlyTrashed()
            ->orderBy('domain')
            ->get();

        if ($servers->isEmpty()
            || ($zonesToUpdate->isEmpty() && $deletedZones->isEmpty())
        ) {
            $this->error('Nothing to do.');
            return false;
        }

        // Prepare local storage for file's creation
        Storage::deleteDirectory($localStoragePath);

        // Generate a file containing zone's name that has been deleted
        $content = $this->generateDeletedZonesContent($zonesToUpdate);
        Storage::put($localStoragePath . '/configuration/deadlist', $content, 'private');

        // Generate one file for zone with its zone definition
        foreach ($zonesToUpdate as $zone) {
            $zoneFilePath = $localStoragePath . '/primary/' . $zone->domain;
            $this->generateZoneFileForZone($zone, $zoneFilePath);
        }

        // Now push files to servers using SFTP
        $error = false;
        foreach ($servers as $server) {
            $error = $error || $this->handleServer($server, $localStoragePath);
        }

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
     * @param string $path
     * @return bool
     */
    public function generateZoneFileForZone(Zone $zone, $path)
    {
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

    public function handleServer(Server $server, $localStoragePath)
    {
        $this->info('Generating Configuration File for ' . $server->hostname);
        $configFilePath = $localStoragePath . '/configuration/' . $server->hostname . '.conf';
        $this->generateConfigFileForServer($server, $configFilePath);

        // Create an array with files that need to be pushed to remote server
        $filesToPush = [
            [
                'local'  => $localStoragePath . '/configuration/' . $server->hostname . '.conf',
                'remote' => Registry::get('ssh_default_remote_path') . '/configuration/named.conf',
            ],
            [
                'local'  => $localStoragePath . '/configuration/deadlist',
                'remote' => Registry::get('ssh_default_remote_path') . '/configuration/deadlist',
            ]
        ];

        if ($server->type == 'master') {
            $localFiles = Storage::files($localStoragePath . '/primary/');
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
     * @param string $path
     * @return bool
     */
    public function generateConfigFileForServer(Server $server, $path)
    {
        // We allow specific templates for each server
        $serverTemplateFileName = 'templates.config_' . $server->hostname;
        $templateFile = view()->exists($serverTemplateFileName)
            ? $serverTemplateFileName
            : 'templates.config_' . $server->type;

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

        $this->info('Has been pushed ' . $pushedFiles . ' of ' . $totalFiles . ' to ' . $server->hostname);
        $sftp->disconnect();

        // Return true if all files has been pushed
        return ($totalFiles == $pushedFiles);
    }
}
