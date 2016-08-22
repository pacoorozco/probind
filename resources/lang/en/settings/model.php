<?php

return [
    'zone_default' => [
        'mname'      => 'SOA Primary Server',
        'mname_help' => 'The fully qualified domain name for the name server.',

        'rname'      => 'Responsible Person',
        'rname_help' => ' The e-mail address of the person in charge of the domain.',

        'refresh'      => 'Refresh time (seconds)',
        'refresh_help' => 'Sets how often the zone should be synchronized from master name server to slave name server.',

        'retry'      => 'Retry time (seconds)',
        'retry_help' => 'Sets how often slave name servers try to synchronize the zone from master name server if synchronization fails.',

        'expire'      => 'Expiration (seconds)',
        'expire_help' => 'Means the period after which the zone expires on slave name servers and slave name servers and slave server stop answering replies until it is synchronized.',

        'negative_ttl'      => 'Negative Answers TTL (seconds)',
        'negative_ttl_help' => 'Specifies the time to live in the zone for caching negative answers on slave servers.',

        'default_ttl'      => 'Default TTL for records (seconds)',
        'default_ttl_help' => 'Specifies the time to live for all records in the zone that do not have an explicit TTL.',
    ],
];
