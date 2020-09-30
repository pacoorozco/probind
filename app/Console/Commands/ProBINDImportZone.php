<?php

namespace App\Console\Commands;

use App\Zone;
use Badcow\DNS\Parser;
use Badcow\DNS\Rdata\SOA;
use Illuminate\Console\Command;

class ProBINDImportZone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'probind:import
                {--domain= : The zone domain name to create}
                {--file= : The file name to import}
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
     */
    public function handle(): void
    {
        // Cast supplied arguments and options.
        $domain = (string) $this->option('domain');
        $filename = (string) $this->option('file');

        // Adds the ending '.' (dot) to the zone name.
        $domain = (substr($domain, -1) != '.') ? $domain . '.' : $domain;

        if (! $this->option('force')) {
            // Check if Zone exists on database.
            $existingZone = Zone::where('domain', $domain)->first();

            if ($existingZone) {
                $this->error('Zone \'' . $existingZone->domain . '\' exists on ProBIND. Use \'--force\' option if you want to import this zone.');

                return;
            }
        }

        // Delete zone, if exists on database.
        $this->deleteZoneIfExists($domain);

        $zoneData = $this->parseFile($domain, $filename);
        $zone = Zone::create([
            'custom_settings' => true,
            'domain' => $domain,
            'reverse_zone' => Zone::isReverseZoneName($domain),
        ]);

        $createdRecordsCount = 0;
        foreach ($zoneData->getResourceRecords() as $record) {
            if ($record instanceof SOA) {
                $zone->update([
                    'serial' => $record->getRdata()->getSerial(),
                    'refresh' => $record->getRdata()->getRefresh(),
                    'retry' => $record->getRdata()->getRetry(),
                    'expire' => $record->getRdata()->getExpire(),
                    'negative_ttl' => $record->getRdata()->getMinimum(),
                ]);
                continue;
            }
            $zone->records()->create([
                'name' => $record->getName(),
                'ttl' => $record->getTtl(),
                'type' => $record->getType(),
                'data' => $record->getRdata()->toText(),
            ]);
            $createdRecordsCount++;
        }

        $this->info('Import zone \'' . $domain . '\' has created with ' . $createdRecordsCount . ' imported records.');
        activity()->log('Import zone <strong>' . $zone->domain . '</strong> has created <strong>' . $createdRecordsCount . '</strong> records.');
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
     * Parses a DNS zone file and returns its content.
     *
     * @param string $domain
     * @param string $filename
     *
     * @return \Badcow\DNS\Zone
     * @throws \ErrorException
     */
    private function parseFile(string $domain, string $filename): \Badcow\DNS\Zone
    {
        try {
            $file = file_get_contents($filename);

            return Parser\Parser::parse($domain, $file);
        } catch (\Exception $exception) {
            throw new \ErrorException();
        }
    }
}
