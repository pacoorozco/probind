<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Services\Formatters;

use App\Enums\ServerType;
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
        $zones = ($server->type == 'master')
            ? Zone::all()
            : Zone::onlyMasterZones();

        return View::first(BINDFormatter::getTemplateNamesForServer($server))
            ->with('server', $server)
            ->with('zones', $zones);
    }

    private static function getTemplateNamesForServer(Server $server): array
    {
        $defaultTemplateName = fn (ServerType $type) => $type == ServerType::Primary
            ? 'bind-templates::defaults.primary-server'
            : 'bind-templates::defaults.secondary-server';

        return [
            'bind-templates::servers.' . str_replace('.', '_', $server->hostname),
            $defaultTemplateName($server->type),
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
        return [
            'bind-templates::zones.' . str_replace('.', '_',
                substr($zone->domain, 0, strrpos($zone->domain, '.'))
            ),
            'bind-templates::defaults.zone',
        ];
    }
}
