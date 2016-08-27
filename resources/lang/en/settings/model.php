<?php

return [
    'zone_default_mname'      => 'SOA Primary Server',
    'zone_default_mname_help' => 'The fully qualified domain name for the name server.',

    'zone_default_rname'      => 'Responsible Person',
    'zone_default_rname_help' => ' The e-mail address of the person in charge of the domain.',

    'zone_default_refresh'      => 'Refresh time (seconds)',
    'zone_default_refresh_help' => 'Sets how often the zone should be synchronized from master name server to slave name server.',

    'zone_default_retry'      => 'Retry time (seconds)',
    'zone_default_retry_help' => 'Sets how often slave name servers try to synchronize the zone from master name server if synchronization fails.',

    'zone_default_expire'      => 'Expiration (seconds)',
    'zone_default_expire_help' => 'Means the period after which the zone expires on slave name servers and slave name servers and slave server stop answering replies until it is synchronized.',

    'zone_default_negative_ttl'      => 'Negative Answers TTL (seconds)',
    'zone_default_negative_ttl_help' => 'Specifies the time to live in the zone for caching negative answers on slave servers.',

    'zone_default_default_ttl'      => 'Default TTL for records (seconds)',
    'zone_default_default_ttl_help' => 'Specifies the time to live for all records in the zone that do not have an explicit TTL.',

    'ssh_default_user' => 'Username',
    'ssh_default_user_help' => 'System user account to manage files and folders for DNS server.',

    'ssh_default_key' => 'Private key',
    'ssh_default_key_help' => 'SSH private key to access to servers.',

    'ssh_default_port' => 'Port number',
    'ssh_default_port_help' => '',

    'ssh_default_remote_path' => 'Remote path',
    'ssh_default_remote_path_help' => 'Path to directory where fields will be pushed.',
];
