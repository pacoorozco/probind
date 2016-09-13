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
                {--force : Delete existing zone before import}';

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

        if (!$this->option('force')) {
            // Check if Zone exists on database.
            $existingZone = Zone::where([
                'domain' => $this->argument('zone')
            ])->first();

            if ($existingZone) {
                $this->error('Zone \'' . $existingZone->domain . '\' exists on ProBIND. Use \'--force\' option if you want to import this zone.');
                return false;
            }
        }

        // Check if Zone exists on database, including trashed zones.
        $existingZone = Zone::withTrashed()
            ->where([
                'domain' => $this->argument('zone')
            ])->first();

        if ($existingZone) {
            $existingZone->forceDelete();
        }

        // Create the zone and fill with parsed data.
        $zoneData = $fileDNS->getZoneData();
        $zone = new Zone();
        $zone->domain = $this->argument('zone');
        $zone->serial = $zoneData['serial'];
        $zone->custom_settings = true;
        $zone->fill(array_only($zoneData, ['refresh', 'retry', 'expire', 'negative_ttl', 'default_ttl']));
        $zone->save();

        // Associate parsed RR
        $records = $fileDNS->getRecords();
        foreach ($records as $item) {
            $zone->records()->create([
                'name'     => $item['name'],
                'ttl'      => $item['ttl'],
                'type'     => $item['type'],
                'priority' => array_get($item, 'options.preference', null),
                'data'     => $item['data']
            ]);
        }

        $this->info('Import zone \'' . $zone->domain . '\' has created with ' . $zone->records()->count() . ' imported records.');
        activity()->log('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $zone->records()->count() . '</strong> records.');

        return true;
    }
}


