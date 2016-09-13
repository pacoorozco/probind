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

use App\Record;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ZoneUnitTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test Zone domain is lower cased
     */
    public function testDomainAttributeIsLowerCased()
    {
        $expectedZone = factory(Zone::class)->make();

        $zone = new Zone([
            'domain' => strtoupper($expectedZone->domain)
        ]);

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
            'has_modifications' => false
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
            'has_modifications' => false
        ]);
        $zonesWithPendingChanges = Zone::withPendingChanges()->get();

        // Pre-condition.
        $this->assertEmpty($zonesWithPendingChanges);

        // Create Zone that are in this scope.
        factory(Zone::class, 5)->create([
            'has_modifications' => true
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
            'master_server' => '192.168.1.3'
        ]);
        $masterZones = Zone::onlyMasterZones()->get();

        // Pre-condition.
        $this->assertEmpty($masterZones);

        // Create Zone that are in this scope
        factory(Zone::class, 5)->create([
            'master_server' => null
        ]);
        $masterZones = Zone::onlyMasterZones()->get();

        $this->assertCount(5, $masterZones);
    }
}
