<?php

namespace App\Console\Commands;

use App\FileDNSParser;
use App\Zone;
use Illuminate\Console\Command;

class ProBINDImportZone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'probind:import 
                {zone : The zone domain name to create}
                {zonefile : The file name to import} 
                {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a BIND zone file to ProBIND';

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
        $fileDNS = new FileDNSParser();
        $fileDNS->load($this->argument('zone'), $this->argument('zonefile'));
        $records = $fileDNS->getRecords();

        $existingZone = Zone::withTrashed()
            ->where([
                'domain' => $this->argument('zone')
            ])->first();

        if ($existingZone) {
            $this->info('Zone \'' . $existingZone->domain . '\' exists on ProBIND.');
        }

        if ($existingZone && $this->option('force')) {
            $this->info('Force option used, \'' . $existingZone->domain . '\' contents will be deleted.');
            $existingZone->forceDelete();
        }

        $zone = Zone::create($fileDNS->getZoneData());
        foreach ($records as $item) {
            $zone->records()->create([
                'name'     => $item['name'],
                'ttl'      => $item['ttl'],
                'type'     => $item['type'],
                'priority' => array_get('options.preference', null),
                'data'     => $item['data']
            ]);
        }

        $this->info('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $zone->records()->count() . '</strong> records.');
        activity()->log('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $zone->records()->count() . '</strong> records.');
    }
}
