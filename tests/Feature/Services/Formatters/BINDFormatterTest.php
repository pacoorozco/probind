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
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Feature\Services\Formatters;

use App\Enums\ServerType;
use App\Models\ResourceRecord;
use App\Models\Server;
use App\Models\Zone;
use App\Services\Formatters\BINDFormatter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BINDFormatterTest extends TestCase
{
    use RefreshDatabase;

    private string $expectedForwardZoneContent = <<< 'ZONE'
;
; This file has been automatically generated using ProBIND v3.

$ORIGIN example.com.
$TTL 172800

@                IN	SOA	dns1.example.com. postmaster.example.com. (
                                         2020010100 ; Serial (aaaammddvv)
                                         86400      ; Refresh
                                         7200       ; Retry
                                         3628800    ; Expire
                                         7200       ; Negative TTL
)

; Name Servers of this zone.
@                                IN	NS	dns1.example.com.
@                                IN	NS	dns2.example.com.

; Resource Records.
services                                 	IN	A	10.0.1.10
services                                 	IN	A	10.0.1.11
ftp                                      	IN	CNAME	services.example.com.
www                                      	IN	CNAME	services.example.com.
@                                        	IN	MX	10 mail-gw1.example.net.
@                                        	IN	MX	20 mail-gw2.example.net.
@                                        	IN	MX	30 mail-gw3.example.net.
@                                        	IN	TXT	"v=spf1 ip4:192.0.2.0/24 ip4:198.51.100.123 a -all"

ZONE;

    private string $expectedReverseZoneContent = <<< 'REVERSEZONE'
;
; This file has been automatically generated using ProBIND v3.

$ORIGIN 1.1.10.in-addr.arpa.
$TTL 172800

@                IN	SOA	dns1.example.com. postmaster.example.com. (
                                         2020010100 ; Serial (aaaammddvv)
                                         86400      ; Refresh
                                         7200       ; Retry
                                         3628800    ; Expire
                                         7200       ; Negative TTL
)

; Name Servers of this zone.
@                                IN	NS	dns1.example.com.
@                                IN	NS	dns2.example.com.

; Resource Records.
1                                        	IN	PTR	server1.example.com.
2                                        	IN	PTR	server2.example.com.
3                                        	IN	PTR	server3.example.com.
4                                        	IN	PTR	server4.example.com.
5                                        	IN	PTR	server5.example.com.

REVERSEZONE;

    private string $expectedPrimaryServerConfigurationContent = <<< 'PRIMARYSERVERCONFIGURATION'
// Primary DNS server - server1.local.

// List of servers that can make transfer requests.
acl "xfer" {
    192.168.194.4;
    192.168.2.203;
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


PRIMARYSERVERCONFIGURATION;

    private string $expectedSecondaryServerConfigurationContent = <<< 'SECONDARYSERVERCONFIGURATION'
// Secondary DNS server - server1.local.

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


SECONDARYSERVERCONFIGURATION;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupAppSettings();

        File::copy('tests/testData/bind-templates/custom-zone-template.blade.php',
            'resources/bind-templates/zones/custom-domain_com.blade.php');

        File::copy('tests/testData/bind-templates/custom-zone-template.blade.php',
            'resources/bind-templates/zones/1_168_192_in-addr_arpa.blade.php');

        File::copy('tests/testData/bind-templates/custom-server-template.blade.php',
            'resources/bind-templates/servers/custom-server_local.blade.php');
    }

    private function setupAppSettings(): void
    {
        setting()->set([
            'zone_default_mname' => 'dns1.example.com',
            'zone_default_rname' => 'postmaster.example.com',
            'zone_default_refresh' => '86400',
            'zone_default_retry' => '7200',
            'zone_default_expire' => '3628800',
            'zone_default_negative_ttl' => '7200',
            'zone_default_default_ttl' => '172800',

            'ssh_default_user' => 'probinder',
            'ssh_default_key' => '-----BEGIN OPENSSH PRIVATE KEY-----',
            'ssh_default_port' => '22',
            'ssh_default_remote_path' => '/home/probinder/data',
        ]);
    }

    public function tearDown(): void
    {
        File::delete('resources/bind-templates/zones/custom-domain_com.blade.php');
        File::delete('resources/bind-templates/zones/1_168_192_in-addr_arpa.blade.php');
        File::delete('resources/bind-templates/servers/custom-server_local.blade.php');

        parent::tearDown();
    }

    /** @test */
    public function it_returns_the_deleted_zones_file_content(): void
    {
        Zone::factory()->count(3)->primary()->create();
        $zones = Zone::all();

        $content = BINDFormatter::getDeletedZonesFileContent($zones);

        // Get the lines of the content
        $lines = explode(PHP_EOL, $content);

        $this->assertCount(3, $lines);

        foreach ($zones as $zone) {
            $this->assertStringContainsString($zone->domain, $content);
        }
    }

    /** @test */
    public function it_returns_empty_deleted_zones_file_content_when_zones_are_empty(): void
    {
        $zones = new Collection();

        $content = BINDFormatter::getDeletedZonesFileContent($zones);

        $this->assertEmpty($content);
    }

    /** @test */
    public function it_returns_a_formatted_zone_file_of_a_forward_zone_using_the_default_template(): void
    {
        $testZone = $this->createTestZone();

        $content = BINDFormatter::getZoneFileContent($testZone);

        $this->assertEquals($this->expectedForwardZoneContent, $content);
    }

    /** @test */
    public function it_returns_a_formatted_zone_file_of_a_reverse_zone_using_the_default_template(): void
    {
        $testZone = $this->createReverseTestZone();

        $content = BINDFormatter::getZoneFileContent($testZone);

        $this->assertEquals($this->expectedReverseZoneContent, $content);
    }

    private function createReverseTestZone(): Zone
    {
        $testZone = Zone::factory()->reverse()->primary()->create([
            'domain' => '1.1.10.in-addr.arpa.',
            'serial' => '2020010100',
            'custom_settings' => true,
            'refresh' => 86400,
            'retry' => 7200,
            'expire' => 3628800,
            'negative_ttl' => 7200,
            'default_ttl' => 172800,
        ]);

        Server::factory()->create([
            'hostname' => 'dns1.example.com',
            'ip_address' => '192.168.1.1',
            'type' => ServerType::Primary,
            'ns_record' => true,
            'active' => true,
        ]);

        Server::factory()->create([
            'hostname' => 'dns2.example.com',
            'ip_address' => '192.168.1.2',
            'type' => ServerType::Secondary,
            'ns_record' => true,
            'active' => true,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $testResourceRecord = ResourceRecord::factory()->asPTRRecord()->make([
                'name' => $i,
                'data' => 'server'.$i.'.example.com.',
            ]);
            $testZone->records()->save($testResourceRecord);
        }

        return $testZone;
    }

    /** @test */
    public function it_returns_a_formatted_zone_file_of_a_forward_zone_using_a_custom_template(): void
    {
        $testZone = Zone::factory()->primary()->create([
            'domain' => 'custom-domain.com.',
            'serial' => '2020010100',
            'custom_settings' => true,
            'refresh' => 86400,
            'retry' => 7200,
            'expire' => 3628800,
            'negative_ttl' => 7200,
            'default_ttl' => 172800,
        ]);

        $expected = <<< 'EXPECTEDZONE'
This is a custom template for zone: custom-domain.com.

EXPECTEDZONE;

        $content = BINDFormatter::getZoneFileContent($testZone);

        $this->assertEquals($expected, $content);
    }

    /** @test */
    public function it_returns_a_formatted_zone_file_of_a_reverse_zone_using_a_custom_template(): void
    {
        $testZone = Zone::factory()->reverse()->primary()->create([
            'domain' => '1.168.192.in-addr.arpa.',
            'serial' => '2020010100',
            'custom_settings' => true,
            'refresh' => 86400,
            'retry' => 7200,
            'expire' => 3628800,
            'negative_ttl' => 7200,
            'default_ttl' => 172800,
        ]);

        $expected = <<< 'EXPECTEDZONE'
This is a custom template for zone: 1.168.192.in-addr.arpa.

EXPECTEDZONE;

        $content = BINDFormatter::getZoneFileContent($testZone);

        $this->assertEquals($expected, $content);
    }

    /** @test */
    public function it_returns_a_formatted_configuration_file_of_a_primary_server_using_the_default_template(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()->create([
            'hostname' => 'server1.local.',
            'type' => ServerType::Primary,
        ]);

        $content = BINDFormatter::getConfigurationFileContent($testServer);

        $this->assertEquals($this->expectedPrimaryServerConfigurationContent, $content);
    }

    /** @test */
    public function it_returns_a_formatted_configuration_file_of_a_secondary_server_using_the_default_template(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()->create([
            'hostname' => 'server1.local.',
            'type' => ServerType::Secondary,
        ]);

        $content = BINDFormatter::getConfigurationFileContent($testServer);

        $this->assertEquals($this->expectedSecondaryServerConfigurationContent, $content);
    }

    /** @test */
    public function it_returns_a_formatted_configuration_file_using_a_custom_template(): void
    {
        /** @var Server $testServer */
        $testServer = Server::factory()->create([
            'hostname' => 'custom-server.local',
        ]);

        $expected = <<< 'EXPECTEDCONFIGURATION'
This is a custom template for server: custom-server.local

EXPECTEDCONFIGURATION;

        $content = BINDFormatter::getConfigurationFileContent($testServer);

        $this->assertEquals($expected, $content);
    }

    private function createTestZone(): Zone
    {
        $testZone = Zone::factory()->primary()->create([
            'domain' => 'example.com.',
            'serial' => '2020010100',
            'custom_settings' => true,
            'refresh' => 86400,
            'retry' => 7200,
            'expire' => 3628800,
            'negative_ttl' => 7200,
            'default_ttl' => 172800,
        ]);

        Server::factory()->create([
            'hostname' => 'dns1.example.com',
            'ip_address' => '192.168.1.1',
            'type' => ServerType::Primary,
            'ns_record' => true,
            'active' => true,
        ]);

        Server::factory()->create([
            'hostname' => 'dns2.example.com',
            'ip_address' => '192.168.1.2',
            'type' => ServerType::Secondary,
            'ns_record' => true,
            'active' => true,
        ]);

        $testResourceRecord = ResourceRecord::factory()->asARecord()->make([
            'name' => 'services',
            'data' => '10.0.1.10',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asARecord()->make([
            'name' => 'services',
            'data' => '10.0.1.11',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asCNAMERecord()->make([
            'name' => 'ftp',
            'data' => 'services.example.com.',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asCNAMERecord()->make([
            'name' => 'www',
            'data' => 'services.example.com.',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asMXRecord()->make([
            'name' => '@',
            'priority' => 10,
            'data' => 'mail-gw1.example.net.',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asMXRecord()->make([
            'name' => '@',
            'priority' => 20,
            'data' => 'mail-gw2.example.net.',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asMXRecord()->make([
            'name' => '@',
            'priority' => 30,
            'data' => 'mail-gw3.example.net.',
        ]);
        $testZone->records()->save($testResourceRecord);

        $testResourceRecord = ResourceRecord::factory()->asTXTRecord()->make([
            'name' => '@',
            'data' => '"v=spf1 ip4:192.0.2.0/24 ip4:198.51.100.123 a -all"',
        ]);
        $testZone->records()->save($testResourceRecord);

        return $testZone;
    }
}
