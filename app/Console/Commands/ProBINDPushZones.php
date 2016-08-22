<?php

namespace App\Console\Commands;

use App\Server;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Registry;
use Storage;
use Symfony\Component\Process\Process;

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
     * @return mixed
     */
    public function handle()
    {
        $zonesToUpdate = Zone::where('updated', 1)
            ->where('master', '')
            ->orderBy('domain')
            ->get();

        if ($zonesToUpdate->isEmpty()) {
            $this->error('Nothing to do');

            return 1;
        }

        $this->info('Generating Zone Files...');

        $this->generateZoneFiles($zonesToUpdate);

        $servers = Server::where('push_updates', 1)
            ->orderBy('hostname')
            ->get();

        foreach ($servers as $server) {
            // Send updates via BASH file
            $command = storage_path('app/scripts/push') . ' --server=' . $server->hostname;

            $this->info(sprintf("Sending updates to server '%s' using '%s'", $server->hostname, $command));

            $process = new Process($command);
            $process->run();
        }
    }

    public function generateZoneFiles($zonesToUpdate)
    {
        $defaults = Registry::all();

        $nameServers = Server::where('ns_record', 1)
            ->orderBy('type')
            ->get();

        Storage::deleteDirectory('probind/');

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

            Storage::put('probind/' . $zone->domain, $contents, 'private');

            $header = sprintf(";\n; This file has been automatically generated using ProBIND v3 on %s.\n;",
                Carbon::now());
            Storage::prepend('probind/' . $zone->domain, $header);

            //$zone->setPendingChanges(false);
        }
    }
}
