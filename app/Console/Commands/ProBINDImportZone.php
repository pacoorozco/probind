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
                {zone : The zone domain to import}
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

        if ($this->option('force')) {
            $zone = Zone::withTrashed()
                ->where([
                    'domain' => $this->argument('zone')
                ])->first();
            if ($zone) {
                $zone->forceDelete();
            }

        }

        $zone = Zone::create($fileDNS->getZoneData());
        foreach ($records as $item) {
            $record = $zone->records()->create([
                'name' => $item['name'],
                'ttl'  => $item['ttl'],
                'type' => $item['type'],
                'data' => $item['data']
            ]);

            if ($item['type'] == 'MX') {
                $record->priority = $item['options']['preference'];
                $record->save();
            }
        }

        activity()->log('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $zone->records()->count() . '</strong> records.');
    }
}
