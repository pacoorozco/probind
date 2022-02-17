{{--
This `secondary-server.blade.php` file is used to render the BIND configuration of secondary servers.

If you modify this template, it will be used to all secondary servers, except if a more specific template exists.

See the README file to know how to modify this template and how to create more specific templates.
--}}

// Secondary DNS server - {{ $server->hostname }}

// List of servers that can make transfer requests.
acl "xfer" {
};

// List of trusted clients that can make revolve requests.
acl "trusted" {
    localhost;
    10/8;
    192.168/16;
};

// List of bogus clients that are used to do "spoofing attacks". See RFC5735.
acl bogusnets {
    0.0.0.0/8;
    127.0.0.0/8;
    169.254.0.0/16;
    172.16.0.0/12;
    192.0.0.0/24;
    192.0.2.0/24;
    192.168.0.0/16;
    224.0.0.0/4;
    240.0.0.0/4;
};

options {
    directory "/etc/bind";
    pid-file  "/etc/bind/configuration/named.pid";
    statistics-file "/etc/bind/statistics/named.stats";
    // In order to increase performance we disable these statistics
    zone-statistics no;

    // Increase zone transfer performance.
    transfer-format many-answers;

    // Maximum time to complete a successful zone transfer.
    max-transfer-time-in 60;

    // See RFC1035
    auth-nxdomain no;

    blackhole { bogusnets; };

    allow-transfer { xfer; };

    allow-query { trusted; };
};

zone "." {
    type hint;
    file "cache/cache";
};

zone "0.0.127.IN-ADDR.ARPA" {
    type master;
    file "primary/127.0.0";

    allow-query {
        any;
    };
};

// Zones not managed by proBIND. Edit the file directly.

include "/etc/bind/configuration/static-zones.conf";

// Zones are managed by proBIND. Do not edit any of these files directly.

@foreach($zones as $zone)
zone "{{ $zone->domain }}" {
    type slave;
    file "secondary/{{ $zone->domain }}";

    masters {
        {{ $master->ip_address }};
    };

    allow-query {
        any;
    };
};
@endforeach
