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
     * @return integer|null
     */
    public function handle()
    {
        $zonesToUpdate = Zone::withPendingChanges()
            ->orderBy('domain')
            ->get();

        if ($zonesToUpdate->isEmpty()) {
            $this->error('Nothing to do');

            return 1;
        }

        Storage::deleteDirectory('probind/');

        $this->info('Generating Deleted Zone Files...');
        $this->generateDeletedZonesFile();

        $this->info('Generating Zone Files...');
        $this->generateZoneFiles($zonesToUpdate);

        $servers = Server::withPushCapability()
            ->orderBy('hostname')
            ->get();

        if ($servers->isEmpty()) {
            $this->error('Nothing to do, servers');

            return 1;
        }

        foreach ($servers as $server) {
            $this->info('Generating Configuration File for ' . $server->hostname);
            $this->generateConfigFile($server);

            if ($server->type == 'master') {
                $this->pushFilesToMasterServer($server, $zonesToUpdate);
            } else {
                $this->pushFilesToSlaveServer($server);
            }
        }

        foreach ($zonesToUpdate as $zone) {
            $zone->setPendingChanges(false);
        }

        $deletedZones = Zone::onlyTrashed()
            ->orderBy('domain')
            ->get();

        foreach ($deletedZones as $zone) {
            $zone->forceDelete();
        }
    }

    public function generateDeletedZonesFile()
    {
        $deletedZones = Zone::onlyTrashed()
            ->orderBy('domain')
            ->get();

        foreach ($deletedZones as $zone) {
            Storage::put('probind/configuration/deadlist', $zone->domain, 'private');
        }
    }

    public function generateZoneFiles($zonesToUpdate)
    {
        $defaults = Registry::all();

        $nameServers = Server::where('ns_record', 1)
            ->orderBy('type')
            ->get();


        foreach ($zonesToUpdate as $zone) {

            $zone->setSerialNumber();

            $records = $zone->records()
                ->orderBy('name')
                ->get();

            $contents = view('templates.zone')
                ->with('servers', $nameServers)
                ->with('defaults', $defaults)
                ->with('zone', $zone)
                ->with('records', $records);

            Storage::put('probind/primary/' . $zone->domain, $contents, 'private');

            $header = sprintf(";\n; This file has been automatically generated using ProBIND v3 on %s.\n;",
                Carbon::now());
            Storage::prepend('probind/primary/' . $zone->domain, $header);

            //$zone->setPendingChanges(false);
        }
    }

    public function generateConfigFile($server)
    {
        if ($server->type == 'master') {
            $zones = Zone::all();

            $contents = view('templates.master_config')
                ->with('server', $server)
                ->with('zones', $zones);
        } else {
            $zones = Zone::onlyMasterZones();
            $master = Server::where('type', 'master')->first();

            $contents = view('templates.slave_config')
                ->with('server', $server)
                ->with('zones', $zones)
                ->with('master', $master);

        }

        Storage::put('probind/configuration/' . $server->hostname . '.conf', $contents, 'private');
    }

    public function pushFilesToMasterServer($server, $zonesToUpdate)
    {
        // Get RSA private key in order to connect to servers
        $privateSSHKey = new RSA();
        $privateSSHKey->loadKey(Registry::get('ssh_default_key'));

        try {
            $sftp = new SFTP($server->hostname, Registry::get('ssh_default_port'));
        } catch (\Exception $e) {
            $this->error('Can not connect to ' . $server->hostname . ': ' . $e->getMessage());

            return false;
        }

        if ( ! $sftp->login(Registry::get('ssh_default_user'), $privateSSHKey)) {
            $this->error('Invalid SSH credentials on ' . $server->hostname);

            return false;
        }

        $this->info('Connected successfully to ' . $server->hostname);

        // Create Remote Folders
        $sftp->mkdir(Registry::get('ssh_default_remote_path') . '/configuration', -1, true);
        $sftp->mkdir(Registry::get('ssh_default_remote_path') . '/primary', -1, true);

        foreach ($zonesToUpdate as $zone) {
            $contents = Storage::get('probind/primary/' . $zone->domain);

            if ( ! $sftp->put(Registry::get('ssh_default_remote_path') . '/primary/' . $zone->domain, $contents)) {
                $this->error('File ' . $zone->domain . ' can not be uploaded on ' . $server->hostname);
            }
        }

        $contents = Storage::get('probind/configuration/' . $server->hostname . '.conf');
        if ( ! $sftp->put(Registry::get('ssh_default_remote_path') . '/configuration/named.conf', $contents)) {
            $this->error('File named.conf can not be uploaded on ' . $server->hostname);
        }

        $contents = Storage::get('probind/configuration/deadlist');
        if ( ! $sftp->put(Registry::get('ssh_default_remote_path') . '/configuration/deadlist', $contents)) {
            $this->error('File deadlist can not be uploaded on ' . $server->hostname);
        }

        $sftp->disconnect();

        return true;
    }

    public function pushFilesToSlaveServer($server)
    {
        // Get RSA private key in order to connect to servers
        $privateSSHKey = new RSA();
        $privateSSHKey->loadKey(Registry::get('ssh_default_key'));

        try {
            $sftp = new SFTP($server->hostname, Registry::get('ssh_default_port'));
        } catch (\Exception $e) {
            $this->error('Can not connect to ' . $server->hostname . ': ' . $e->getMessage());

            return false;
        }

        if ( ! $sftp->login(Registry::get('ssh_default_user'), $privateSSHKey)) {
            $this->error('Invalid SSH credentials on ' . $server->hostname);

            return false;
        }

        $this->info('Connected successfully to ' . $server->hostname);

        // Create Remote Folders
        $sftp->mkdir(Registry::get('ssh_default_remote_path') . '/configuration', -1, true);

        $contents = Storage::get('probind/configuration/' . $server->hostname . '.conf');
        if ( ! $sftp->put(Registry::get('ssh_default_remote_path') . '/configuration/named.conf', $contents)) {
            $this->error('File named.conf can not be uploaded on ' . $server->hostname);
        }

        $contents = Storage::get('probind/configuration/deadlist');
        if ( ! $sftp->put(Registry::get('ssh_default_remote_path') . '/configuration/deadlist', $contents)) {
            $this->error('File deadlist can not be uploaded on ' . $server->hostname);
        }

        $sftp->disconnect();

        return true;
    }
}
