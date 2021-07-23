<?php

namespace App\Console\Commands;

use App\Zone;
use Badcow\DNS\Parser;
use Badcow\DNS\Rdata\SOA;
use Illuminate\Console\Command;

class ProBINDImportZone extends Command
{
    const SUCCESS_CODE = 0;
    const ERROR_PARSING_FILE_CODE = 1;
    const ERROR_EXISTING_ZONE_CODE = 2;

    protected $signature = 'probind:import
                {--domain= : The zone domain name to create}
                {--file= : The file name to import}';

    protected $description = 'Imports a BIND zone file to ProBIND';

    public function handle(): int
    {
        $domain = $this->ensureFQDN($this->option('domain'));

        if (Zone::where('domain', $domain)->exists()) {
            $this->error('Zone can not be imported. A zone for the provided domain already exists.');

            return self::ERROR_EXISTING_ZONE_CODE;
        }

        try {
            $zoneData = $this->parseFile($domain, $this->option('file'));
        } catch (\Throwable $exception) {
            $this->error('The provided file could not be parsed.');

            return self::ERROR_PARSING_FILE_CODE;
        }

        /** @var Zone $zone */
        $zone = Zone::create([
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

        $this->info('A zone for ' . $domain . ' domain has been created. ' . $createdRecordsCount . ' records has been imported.');
        activity()->log('Created zone <strong>' . $zone->domain . '</strong> by importing <strong>' . $createdRecordsCount . '</strong> records.');

        return self::SUCCESS_CODE;
    }

    private function ensureFQDN(string $domain): string
    {
        return (substr($domain, -1) != '.') ? $domain . '.' : $domain;
    }

    /**
     * Parses a DNS zone file and returns its content.
     *
     * @param  string  $domain
     * @param  string  $filename
     *
     * @return \Badcow\DNS\Zone
     * @throws \Badcow\DNS\Parser\ParseException
     */
    private function parseFile(string $domain, string $filename): \Badcow\DNS\Zone
    {
        return Parser\Parser::parse($domain, file_get_contents($filename));
    }
}
