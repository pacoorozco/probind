<?php

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
    public function testCreateSerialNumber()
    {
        $expectedSerial = intval(Carbon::now()->format('Ymd') . '01');
        $serial = Zone::createSerialNumber();
        $this->assertEquals($expectedSerial, $serial);
    }

    /**
     * Test isMasterZone() function
     */
    public function testIsMasterZone()
    {
        $expectedZone = factory(Zone::class)->make();

        // Set a master Zone
        $expectedZone->master = null;
        $this->assertTrue($expectedZone->isMasterZone());

        // Set an slave Zone
        $expectedZone->master = '192.168.1.2';
        $this->assertFalse($expectedZone->isMasterZone());
    }

    /**
     * Test hasPendingChanges() function
     */
    public function testHasPendingChanges()
    {
        $expectedZone = factory(Zone::class)->make();

        // Set pending changes
        $expectedZone->updated = true;
        $this->assertTrue($expectedZone->hasPendingChanges());

        // Clear pending changes
        $expectedZone->updated = false;
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
     * Test Zone setSerialNumber() function
     */
    public function testSetSerialNumber()
    {
        $expectedSerial = intval(Carbon::now()->format('Ymd') . '01');

        // Create a Zone without pending changes
        $zone = factory(Zone::class)->create([
            'updated' => false
        ]);
        $zone->setSerialNumber();
        $this->assertEquals($expectedSerial, $zone->serial);

        // Call again, but serial will be the same, there are pending changes
        $zone->setSerialNumber();
        $this->assertEquals($expectedSerial, $zone->serial);

        // Simulate a push to servers, so pending changes are false
        $zone->setPendingChanges(false);
        $zone->setSerialNumber();
        $this->assertNotEquals($expectedSerial, $zone->serial);

        // Use force option on setSerialNumber()
        $zone->serial = $expectedSerial;
        $zone->setSerialNumber(true);
        $this->assertNotEquals($expectedSerial, $zone->serial);
    }

    public function testSetPendingChanges()
    {
        // Create a Zone without pending changes
        $zone = factory(Zone::class)->create([
            'updated' => false
        ]);
        $this->assertFalse($zone->hasPendingChanges());

        // Set pending changes
        $zone->setPendingChanges(true);
        $this->assertTrue($zone->hasPendingChanges());
    }

    /**
     * Test Scope withPendingChanges()
     */
    public function testScopeWithPendingChanges()
    {
        // Create Zone that are out this scope
        factory(Zone::class, 5)->create([
            'updated' => false
        ]);
        $zonesWithPendingChanges = Zone::withPendingChanges()->get();
        $this->assertEmpty($zonesWithPendingChanges);

        // Create Zone that are in this scope
        factory(Zone::class, 5)->create([
            'updated' => true,
            'master'  => null
        ]);
        $zonesWithPendingChanges = Zone::withPendingChanges()->get();
        $this->assertCount(5, $zonesWithPendingChanges);
    }

    public function testScopeOnlyMasterZones()
    {
        // Create Zone that are out this scope
        factory(Zone::class, 5)->create([
            'master' => '192.168.1.3'
        ]);
        $masterZones = Zone::onlyMasterZones()->get();
        $this->assertEmpty($masterZones);

        // Create Zone that are in this scope
        factory(Zone::class, 5)->create([
            'master' => null
        ]);
        $masterZones = Zone::onlyMasterZones()->get();
        $this->assertCount(5, $masterZones);
    }
}
