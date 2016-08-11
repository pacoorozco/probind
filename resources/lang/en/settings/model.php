<?php

return [

    'zone_default'       => [
        'mname'      => 'Hostname for SOA record',
        'mname_help' => 'The origin of domains managed in this database, as published in the SOA records. This would usually be the hostname of the master DNS server, e.g. ns1.mydomain.net.',

        'rname'      => 'Email address for SOA record',
        'rname_help' => 'If you have a "hostmaster" alias which forwards to the DNS staff, put "hostmaster" in here. e.g. hostmaster@mydomain.net.',

        'refresh'      => 'Refresh',
        'refresh_help' => '',

        'retry'      => 'Retry',
        'retry_help' => '',

        'expire'      => 'Expire',
        'expire_help' => '',

        'minimum_ttl'      => 'Minimum TTL',
        'minimum_ttl_help' => '',
    ],
    'record_ttl_default' => 'TTL',

];