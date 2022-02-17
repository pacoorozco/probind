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
use Spatie\TestTime\TestTime;
use Tests\TestCase;

class BINDFormatterTest extends TestCase
{
    use RefreshDatabase;

    private string $expected = <<< 'ZONE'
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

    public function setUp(): void
    {
        parent::setUp();

        $this->setupAppSettings();

        File::copy('tests/testData/bind-templates/custom-zone-template.blade.php',
            'resources/bind-templates/zones/custom-domain_com.blade.php');
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

        parent::tearDown();
    }

    /** @test */
    public function it_returns_the_deleted_zones_file_content()
    {
        $zones = Zone::factory()->count(3)->primary()->create();

        $content = BINDFormatter::getDeletedZonesFileContent($zones);

        // Get the lines of the content
        $lines = explode(PHP_EOL, $content);

        $this->assertCount(3, $lines);

        foreach ($zones as $zone) {
            $this->assertStringContainsString($zone->domain, $content);
        }
    }

    /** @test */
    public function it_returns_empty_deleted_zones_file_content_when_zones_are_empty()
    {
        $zones = new Collection();

        $content = BINDFormatter::getDeletedZonesFileContent($zones);

        $this->assertEmpty($content);
    }

    /** @test */
    public function it_returns_a_formatted_zone_file_using_the_default_template()
    {
        $testZone = $this->createTestZone();

        $content = BINDFormatter::getZoneFileContent($testZone);

        $this->assertEquals($this->expected, $content);
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

    /** @test */
    public function it_returns_a_formatted_zone_file_using_a_custom_template()
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
}
