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

namespace App\Services\Formatters;

use App\Models\Server;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

/**
 * BINDFormatter formats the data ensuring compatibility with the BIND format.
 *
 * @see https://downloads.isc.org/isc/bind9/9.17.19/doc/arm/html/reference.html#zone-file
 */
class BINDFormatter
{
    public static function getDeletedZonesFileContent(Collection $deletedZones): string
    {
        return $deletedZones
            ->pluck('domain')
            ->join(PHP_EOL);
    }

    public static function getConfigurationFileContent(Server $server): string
    {
        $zones = $server->isPrimary()
            ? Zone::all()
            : Zone::primaryZones();

        return View::first(BINDFormatter::getTemplateNamesForServer($server))
            ->with('server', $server)
            ->with('zones', $zones)
            ->render();
    }

    private static function getTemplateNamesForServer(Server $server): array
    {
        return [
            'bind-templates::servers.' . str_replace('.', '_', $server->hostname),
            $server->isPrimary()
                ? 'bind-templates::defaults.primary-server'
                : 'bind-templates::defaults.secondary-server',
        ];
    }

    public static function getZoneFileContent(Zone $zone): string
    {
        // Get all Name Servers that had to be on NS records
        $nameServers = Server::shouldBePresentAsNameserver()
            ->get();

        // Get all records
        $records = $zone->records()
            ->orderBy('type')
            ->get();

        // Create file content with a blade view
        return View::first(BINDFormatter::getTemplateNamesForZone($zone))
            ->with('zone', $zone)
            ->with('servers', $nameServers)
            ->with('records', $records)
            ->render();
    }

    private static function getTemplateNamesForZone(Zone $zone): array
    {
        $domain = str_ends_with($zone->domain, '.')
            ? substr($zone->domain, 0, -1)
            : $zone->domain;

        return [
            'bind-templates::zones.' . str_replace('.', '_', $domain),
            'bind-templates::defaults.zone',
        ];
    }
}
