<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace App\Console\Commands;

use App\Jobs\CreateZone;
use App\Models\Zone;
use Badcow\DNS\Parser;
use Badcow\DNS\Parser\ParseException;
use Badcow\DNS\Rdata\SOA;
use Illuminate\Console\Command;

class ImportZone extends Command
{
    const SUCCESS_CODE = 0;

    const ERROR_PARSING_FILE_CODE = 1;

    const ERROR_EXISTING_ZONE_CODE = 2;

    const ERROR_INVALID_PARAMETER = 9;

    protected $signature = 'probind:import
                {--domain= : The domain name of the Zone to be created}
                {--file= : The filename to import}';

    protected $description = 'Imports a BIND zone file into ProBIND';

    public function handle(): int
    {
        $domain = $this->option('domain');
        if (! is_string($domain)) {
            $this->error('--domain option is not valid.');

            return self::ERROR_INVALID_PARAMETER;
        }
        $domain = $this->ensureFQDN($domain);

        $filename = $this->option('file');
        if (! is_string($filename)) {
            $this->error('--file option is not valid.');

            return self::ERROR_INVALID_PARAMETER;
        }

        if (Zone::where('domain', $domain)->first()) {
            $this->error('Zone can not be imported. A zone for the provided domain already exists.');

            return self::ERROR_EXISTING_ZONE_CODE;
        }

        try {
            $zoneData = $this->parseFile($domain, $filename);
        } catch (\Throwable $exception) {
            $this->error('Provided BIND zone file could not be parsed.');

            return self::ERROR_PARSING_FILE_CODE;
        }

        /** @var Zone $zone */
        $zone = CreateZone::dispatchSync($domain);

        $createdRecordsCount = 0;
        foreach ($zoneData->getResourceRecords() as $record) {
            if ($record->getRdata() instanceof SOA) {
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
                'data' => $record->getRdata()?->toText(),
            ]);
            $createdRecordsCount++;
        }

        $this->info('A zone for '.$domain.' domain has been created. '.$createdRecordsCount.' records has been imported.');
        activity()->log('Created zone <strong>'.$zone->domain.'</strong> by importing <strong>'.$createdRecordsCount.'</strong> records.');

        return self::SUCCESS_CODE;
    }

    private function ensureFQDN(string $domain): string
    {
        return (! str_ends_with($domain, '.')) ? $domain.'.' : $domain;
    }

    /**
     * Parses a DNS zone file and returns its content.
     *
     *
     * @throws \Badcow\DNS\Parser\ParseException
     */
    private function parseFile(string $domain, string $filename): \Badcow\DNS\Zone
    {
        $content = file_get_contents($filename);
        if (false === $content) {
            throw new ParseException();
        }

        return Parser\Parser::parse($domain, $content);
    }
}
