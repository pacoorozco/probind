<?php

namespace App\Console\Commands;

use App\FileDNSParser;
use App\Zone;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
     * Delete the specified zone by domain search if exists.
     *
     * @param string $domain
     */
    private function deleteZoneIfExists(string $domain): void
    {
        // Check if Zone exists on database, including trashed zones.
        $existingZone = Zone::withTrashed()
            ->where('domain', $domain)->first();

        if ($existingZone) {
            $existingZone->forceDelete();
        }
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(): void
    {
        // Cast supplied arguments and options.
        $domain = (string)$this->argument('zone');
        $zonefile = (string)$this->argument('zonefile');

        $fileDNS = new FileDNSParser($domain);
        $fileDNS->load($zonefile);

        if (!$this->option('force')) {
            // Check if Zone exists on database.
            $existingZone = Zone::where('domain', $domain)->first();

            if ($existingZone) {
                $this->error('Zone \'' . $existingZone->domain . '\' exists on ProBIND. Use \'--force\' option if you want to import this zone.');
                return;
            }
        }

        // Delete zone, if exists on database.
        $this->deleteZoneIfExists($domain);

        // Create the zone and fill with parsed data.
        $zoneData = $fileDNS->getZoneData();
        $zone = new Zone();
        $zone->domain = $domain;
        $zone->reverse_zone = Zone::validateReverseDomainName($domain);
        $zone->serial = $zoneData['serial'];
        $zone->custom_settings = true;
        $zone->fill(Arr::only($zoneData, ['refresh', 'retry', 'expire', 'negative_ttl', 'default_ttl']));
        $zone->save();

        // Associate parsed RR
        $records = $fileDNS->getRecords();
        foreach ($records as $item) {
            $zone->records()->create([
                'name' => $item['name'],
                'ttl' => $item['ttl'],
                'type' => $item['type'],
                'priority' => array_get($item, 'options.preference', null),
                'data' => $item['data']
            ]);
        }

        $this->info('Import zone \'' . $zone->domain . '\' has created with ' . $zone->records()->count() . ' imported records.');
        activity()->log('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $zone->records()->count() . '</strong> records.');
        return;
    }
}


