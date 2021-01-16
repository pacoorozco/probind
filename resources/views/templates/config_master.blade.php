// Fitxer de configuracio BIND
// Configuracio primari - {{ $server->hostname }}

// Fixem les llistes de control acces (ACL)
acl "xfer" {
    192.168.194.4;
    192.168.2.203;
};

acl "trusted" {
// Cal posar aqui la llista de clients en els
// que confiem. Seran aquells que puguin enviar
// peticions DNS.
    localhost;
    10/8;
    192.168/16;
};

acl bogusnets {
// Es bloqueja tot l'espais d'adreces definit
// al RFC5735 com a xarxes IP falses, normalment
// usades pe a "spoofing attacks"
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

// Configuracio de les opcions de seguretat
options {
    directory "/etc/mapes";
    pid-file  "/etc/mapes/configuration/named.pid";
    statistics-file "/etc/mapes/statistics/named.stats";
    // In order to increase performance we disable these statistics
    zone-statistics no;

    // Genera una __ferencia de zones mes eficient
    // Coloca mes d'un registre DNS en el mateix
    // missatge, en comptes de nomes un.
    __fer-format many-answers;

    // Fixa el maxim temps per a la __ferencia
    // d'una zona. En cas de que trigui mes la
    // considera no completada.
    max-__fer-time-in 60;

    // Seguim en aquest cas la RFC1035
    auth-nxdomain no;

    // No tenim interfaces dinamics, per tant
    // evitem que BIND vagi mirant si estan UP|DOWN
    interface-interval 0;

    blackhole { bogusnets; };

    allow-__fer {
        // Per defecte limitem la __ferencia
        // de zones a ACL "xfer"
        xfer;
    };

    allow-query {
        // Acceptem les peticions del ACL "trusted"
        // Permetre les peticions de tothom a les
        // primary zones.
        trusted;
    };
};

// Comenca la definicio de zones
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

// Entrades estatiques, no gestionades per PROBIND
// Cal modificar-les al fitxer seguent, tots els mapes
// estaran sota /etc/mapes/static-zones

include "/etc/mapes/configuration/static-zones.conf";

// Entrades generades per PROBIND
@foreach($zones as $zone)
zone "{{ $zone->domain }}" {
    @if ($zone->isMasterZone())
    type master;
    file "primary/{{ $zone->domain }}";

    allow-query {
        any;
    };
    @else
    type slave;
    file "secondary/{{ $zone->domain }}";

    masters {
        {{ $zone->master }};
    };
    @endif

    allow-query {
        any;
    };
};
@endforeach
