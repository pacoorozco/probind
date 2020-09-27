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

namespace Tests\Unit;

use App\Record;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ZoneUnitTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test Zone domain is lower cased
     */
    public function testDomainAttributeIsLowerCased()
    {
        $expectedZone = factory(Zone::class)->make();

        $zone = new Zone();
        $zone->domain = strtoupper($expectedZone->domain);

        // Attribute must be lower cased
        $this->assertEquals($expectedZone->domain, $zone->domain);
    }

    /**
     * Test Zone serial creation
     */
    public function testGenerateSerialNumber()
    {
        $expectedSerial = intval(Carbon::now()->format('Ymd') . '00');
        $serial = Zone::generateSerialNumber();
        $this->assertEquals($expectedSerial, $serial);
    }

    /**
     * Test isMasterZone() function
     */
    public function testIsMasterZone()
    {
        $expectedZone = factory(Zone::class)->make();

        // Set a master Zone
        $expectedZone->master_server = null;
        $this->assertTrue($expectedZone->isMasterZone());

        // Set an slave Zone
        $expectedZone->master_server = '192.168.1.2';
        $this->assertFalse($expectedZone->isMasterZone());
    }

    /**
     * Test getTypeOfZone() function
     */
    public function testGetTypeOfZone()
    {
        $expectedZone = factory(Zone::class)->make();

        // Set a master Zone
        $expectedZone->master_server = null;
        $this->assertEquals('master', $expectedZone->getTypeOfZone());

        // Set an slave Zone
        $expectedZone->master_server = '192.168.1.2';
        $this->assertEquals('slave', $expectedZone->getTypeOfZone());
    }

    /**
     * Test hasPendingChanges() function
     */
    public function testHasPendingChanges()
    {
        $expectedZone = factory(Zone::class)->make();

        // Set pending changes
        $expectedZone->has_modifications = true;
        $this->assertTrue($expectedZone->hasPendingChanges());

        // Clear pending changes
        $expectedZone->has_modifications = false;
        $this->assertFalse($expectedZone->hasPendingChanges());
    }

    /**
     * Test Record relationship
     */
    public function testRecordRelationship()
    {
        $expectedZone = factory(Zone::class)->create();
        $this->assertEmpty($expectedZone->records()->get());

        // Add some records to this zone
        $records = factory(Record::class, 'A', 10)->make();
        $expectedZone->records()->saveMany($records);
        $this->assertCount(10, $expectedZone->records()->get());
    }

    /**
     * Provides a data set for record count test.
     *
     * @return array
     */
    public function recordCountDataSet(): array
    {
        return [
            'zero records' => [0, 0],
            'one record' => [1, 1],
            'a hundred records' => [100, 100],
        ];
    }

    /**
     * @test
     * @dataProvider recordCountDataSet
     *
     * @param int $input
     * @param int $want
     */
    public function get_record_count_attribute(int $input, int $want): void
    {
        $zone = factory(Zone::class)->create();
        $zone->records()->saveMany(factory(Record::class, 'A', $input)->make());
        $this->assertSame($want, $zone->records_count);
    }

    /**
     * Test Zone raiseSerialNumber() function
     */
    public function testRaiseSerialNumber()
    {
        // Create a Zone without pending changes
        $zone = factory(Zone::class)->create();
        $expectedSerial = $zone->serial;
        $zone->setPendingChanges(false);

        // Raise Serial Number, has to be bigger than expected.
        $zone->getNewSerialNumber();
        $this->assertGreaterThan($expectedSerial, $zone->serial);

        // Call again, but serial will be the same, there are still pending changes.
        $expectedSerial = $zone->serial;
        $zone->getNewSerialNumber();
        $this->assertEquals($expectedSerial, $zone->serial);

        // Simulate a push to servers, so pending changes are false.
        $zone->setPendingChanges(false);

        // Now, raise Serial Number will be get a greater one.
        $expectedSerial = $zone->serial;
        $zone->getNewSerialNumber();
        $this->assertGreaterThan($expectedSerial, $zone->serial);

        // Use force option on getNewSerialNumber()
        $zone->serial = $expectedSerial;
        $zone->getNewSerialNumber(true);
        $this->assertGreaterThan($expectedSerial, $zone->serial);

        // Create a low Serial Number and raise serial
        $zone->serial = 2010010100;
        $expectedSerial = Zone::generateSerialNumber();
        $zone->getNewSerialNumber(true);
        $this->assertEquals($expectedSerial, $zone->serial);
    }

    /**
     * Test setPendingChanges() method
     */
    public function testSetPendingChanges()
    {
        // Create a Zone without pending changes
        $zone = factory(Zone::class)->create([
            'has_modifications' => false,
        ]);
        // Pre-condition.
        $this->assertFalse($zone->hasPendingChanges());

        // Set pending changes.
        $zone->setPendingChanges(true);
        $this->assertTrue($zone->hasPendingChanges());
    }

    /**
     * Test Scope withPendingChanges()
     */
    public function testScopeWithPendingChanges()
    {
        // Create Zone that are out this scope.
        factory(Zone::class, 5)->create([
            'has_modifications' => false,
        ]);
        $zonesWithPendingChanges = Zone::withPendingChanges()->get();

        // Pre-condition.
        $this->assertEmpty($zonesWithPendingChanges);

        // Create Zone that are in this scope.
        factory(Zone::class, 5)->create([
            'has_modifications' => true,
        ]);
        $zonesWithPendingChanges = Zone::withPendingChanges()->get();

        $this->assertCount(5, $zonesWithPendingChanges);
    }

    /**
     * Test scope onlyMasterZones()
     */
    public function testScopeOnlyMasterZones()
    {
        // Create Zone that are out this scope
        factory(Zone::class, 5)->create([
            'master_server' => '192.168.1.3',
        ]);
        $masterZones = Zone::onlyMasterZones()->get();

        // Pre-condition.
        $this->assertEmpty($masterZones);

        // Create Zone that are in this scope
        factory(Zone::class, 5)->create([
            'master_server' => null,
        ]);
        $masterZones = Zone::onlyMasterZones()->get();

        $this->assertCount(5, $masterZones);
    }

    /**
     * Data Set for Zone name validation.
     *
     * @return array
     */
    public function zoneNameDataSet(): array
    {
        return [
            // 'name of the test case' => ['input', 'expected']
            'second level domain' => ['domain.com.', true],
            'third level domain' => ['sub.domain.com.', true],
            'short root TLD' => ['invali.d.', true],
            'domain with punycode' => ['xn--domain.com.', true],
            'IPv4 reverse domain' => ['10.10.10.in-addr.arpa.', true],
            'IPv6 reverse domain' => ['1.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa.', true],

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
     * Test for FQDN zone names.
     *
     * @test
     * @dataProvider zoneNameDataSet
     *
     * @param string $input
     * @param bool   $want
     */
    public function validates_zone_name(string $input, bool $want): void
    {
        $this->assertEquals($want, Zone::isValidZoneName($input));
    }

    /**
     * Data Set for reverse Zone name validation.
     *
     * @return array
     */
    public function zoneReverseZoneDataSet(): array
    {
        return [
            // 'name of the test case' => ['input', 'expected']
            'IPv4 first level' => ['10.in-addr.arpa.', true],
            'IPv4 second level' => ['11.10.in-addr.arpa.', true],
            'IPV4 third level' => ['12.11.10.in-addr.arpa.', true],
            'IPv6 reverse domain' => ['1.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.0.ip6.arpa.', true],

            'too short' => ['.', false],
            'non arpa domain' => ['domain.com.', false],
            'without ending dot' => ['10.in-addr.arpa', false],
            'domain too long' => ['abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ.in-addr.arpa.', false],
            'invalid chars' => ['0123456789 +-.,!@#$%^&*();\\/|<>\"\\.in-addr.arpa.', false],
            'spaces' => ['12345 -98.7 3.141 .6180 9,000 +42.in-addr.arpa.', false],
            'two consecutive dots' => ['10..in-addr.arpa..', false],
        ];
    }

    /**
     * Test for Reverse zone name validation.
     */
    /**
     * Test for reverse zone names.
     *
     * @test
     * @dataProvider zoneReverseZoneDataSet
     *
     * @param string $input
     * @param bool   $want
     */
    public function validates_reverse_zone_name(string $input, bool $want): void
    {
        $this->assertEquals($want, Zone::isReverseZoneName($input));

    }

    /**
     * Test getValidRecordTypesForThisZone()
     */
    public function testGetValidRecordTypesForThisZone()
    {
        $zone = new Zone();

        $zone->reverse_zone = true;
        $this->assertArrayHasKey('PTR', $zone->getValidRecordTypesForThisZone());
        $this->assertArrayHasKey('NS', $zone->getValidRecordTypesForThisZone());
        $this->assertArrayNotHasKey('A', $zone->getValidRecordTypesForThisZone());

        $zone->reverse_zone = false;
        $this->assertArrayHasKey('A', $zone->getValidRecordTypesForThisZone());
        $this->assertArrayHasKey('CNAME', $zone->getValidRecordTypesForThisZone());
        $this->assertArrayHasKey('NS', $zone->getValidRecordTypesForThisZone());
        $this->assertArrayNotHasKey('PTR', $zone->getValidRecordTypesForThisZone());
    }
}
