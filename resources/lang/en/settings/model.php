<?php

return [

    'zone_default'            => [
        'mname'      => 'SOA Primary Server',
        'mname_help' => 'The fully qualified domain name for the name server.',

        'rname'      => 'Responsible Person',
        'rname_help' => ' The e-mail address of the person in charge of the domain.',

        'refresh'      => 'Refresh Interval',
        'refresh_help' => 'The interval at which a secondary server checks for zone updates.',

        'retry'      => 'Retry Interval',
        'retry_help' => 'The time the secondary server waits after a failure to download the zone database.',

        'expire'      => 'Expires After',
        'expire_help' => 'The period of time for which zone information is valid on the secondary server.',

        'minimum_ttl'      => 'Minimum (Default) TTL',
        'minimum_ttl_help' => 'The minimum time-to-live value for cached records on a DNS server.',
    ],
    'record_ttl_default'      => 'TTL',
    'record_ttl_default_help' => 'The default time-to-live value for all resource records.',

];
