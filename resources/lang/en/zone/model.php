<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */

return [
    'domain'          => 'Zone name (FQDN)',
    'serial'          => 'Serial',
    'server'   => 'Primary DNS (server IP)',
    'type'            => 'Type',
    'types'           => [
        'master' => 'Primary',
        'slave'  => 'Secondary',
    ],
    'custom_settings' => 'Use specific settings for this zone',
    'copy_values_from_defaults' => 'Copy values from defaults',

    'refresh'      => 'Refresh time (seconds)',
    'refresh_help' => 'Sets how often the zone should be synchronized from primary name server to secondary name server.',

    'retry'      => 'Retry time (seconds)',
    'retry_help' => 'Sets how often secondary name servers try to synchronize the zone from primary name server if synchronization fails.',

    'expire'      => 'Expiration (seconds)',
    'expire_help' => 'Means the period after which the zone expires on secondary name servers.',

    'negative_ttl'      => 'Negative Answers TTL (seconds)',
    'negative_ttl_help' => 'Specifies the time to live in the zone for caching negative answers on secondary servers.',

    'default_ttl'      => 'Default TTL for records (seconds)',
    'default_ttl_help' => 'Specifies the time to live for all records in the zone that do not have an explicit TTL.',

    'created_at' => 'Created at',
    'updated_at' => 'Last updated at',

    'status' => 'Status',
    'status_list' => [
        'synced' => 'Synced',
        'unsynced' => 'Needs sync',
    ],
];
