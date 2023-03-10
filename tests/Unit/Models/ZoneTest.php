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

namespace Tests\Unit\Models;

use App\Enums\ZoneType;
use App\Models\Zone;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider providesDomainNames
     */
    public function domain_name_is_lowercase(string $testDomainName, string $want): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->make([
            'domain' => $testDomainName,
        ]);

        $this->assertEquals($want, $zone->domain);
    }

    public static function providesDomainNames(): array
    {
        return [
            // 'name of the test case' => ['input', 'expected']
            'lowercase domain name' => ['foo.bar.com', 'foo.bar.com'],
            'uppercase domain name' => ['FOO.BAR.COM', 'foo.bar.com'],
            'mixed-case domain name' => ['foo.BAR.com', 'foo.bar.com'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider providesZoneName
     */
    public function validates_forward_zone_name(string $testZoneName, bool $want): void
    {
        $this->assertEquals($want, Zone::isValidZoneName($testZoneName));
    }

    public static function providesZoneName(): array
    {
        return [
            // 'name of the test case' => ['input', 'expected']
            'second level domain' => ['domain.com.', true],
            'third level domain' => ['sub.domain.com.', true],
            'short root TLD' => ['invali.d.', true],
            'domain with punycode' => ['xn--domain.com.', true],
            'IPv4 reverse domain' => ['10.10.10.in-addr.arpa.', true],
            'IPv6 reverse domain' => [
                '1.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa.', true,
            ],

            'too short' => ['.', false],
            'without ending dot' => ['domain.com', false],
            'domain too long' => ['abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ', false],
            'invalid chars' => ['0123456789 +-.,!@#$%^&*();\\/|<>\"\\', false],
            'spaces' => ['12345 -98.7 3.141 .6180 9,000 +42', false],
            'two consecutive dots' => ['domain..com.', false],
            'two consecutive underscores' => ['domain___.com.', false],
        ];
    }

    /**
     * @test
     *
     * @dataProvider providesReverseZoneNames
     */
    public function validates_reverse_zone_name(string $testZoneName, bool $want): void
    {
        $this->assertEquals($want, Zone::isReverseZoneName($testZoneName));
    }

    public static function providesReverseZoneNames(): array
    {
        return [
            // 'name of the test case' => ['input', 'expected']
            'IPv4 first level' => ['10.in-addr.arpa.', true],
            'IPv4 second level' => ['11.10.in-addr.arpa.', true],
            'IPV4 third level' => ['12.11.10.in-addr.arpa.', true],
            'IPv6 reverse domain' => [
                '1.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa.', true,
            ],

            'too short' => ['.', false],
            'non arpa domain' => ['domain.com.', false],
            'without ending dot' => ['10.in-addr.arpa', false],
            'domain too long' => ['abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ.in-addr.arpa.', false],
            'invalid chars' => ['0123456789 +-.,!@#$%^&*();\\/|<>\"\\.in-addr.arpa.', false],
            'spaces' => ['12345 -98.7 3.141 .6180 9,000 +42.in-addr.arpa.', false],
            'two consecutive dots' => ['10..in-addr.arpa..', false],
        ];
    }

    /** @test */
    public function it_returns_true_on_master_zones(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->make();

        $this->assertTrue($zone->isPrimary());
    }

    /** @test */
    public function it_returns_false_on_secondary_zones(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->secondary()->make();

        $this->assertFalse($zone->isPrimary());
    }

    /** @test */
    public function it_returns_proper_zone_type_on_primary_zones(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->primary()->make();

        $this->assertEquals(ZoneType::Primary, $zone->getTypeOfZone());
    }

    /** @test */
    public function it_returns_proper_zone_type_on_secondary_zones(): void
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->secondary()->make();

        $this->assertEquals(ZoneType::Secondary, $zone->getTypeOfZone());
    }
}
