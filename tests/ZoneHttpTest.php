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

use App\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ZoneHttpTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test a successful new Master Zone creation
     */
    public function testNewMasterZoneCreationSuccess()
    {
        $this->visit('zones/create')
            ->type('master.com', 'domain')
            ->check('custom_settings')
            ->type('10', 'refresh')
            ->type('10', 'retry')
            ->type('10', 'expire')
            ->type('10', 'negative_ttl')
            ->type('10', 'default_ttl')
            ->press('master_zone');

        // Get from DB if Zone has been created.
        $zone = Zone::where('domain', 'master.com')
            ->where('refresh', 10)
            ->where('retry', 10)
            ->where('negative_ttl', 10)
            ->where('default_ttl', 10)
            ->first();

        $this->assertNotNull($zone);
    }

    /**
     * Test a successful new Slave Zone creation
     */
    public function testNewSlaveZoneCreationSuccess()
    {
        $this->visit('zones/create')
            ->type('slave.com', 'domain')
            ->type('192.168.1.3', 'master')
            ->press('slave_zone');

        // Get from DB if Zone has been created.
        $zone = Zone::where('domain', 'slave.com')
            ->where('master', '192.168.1.3')
            ->first();

        $this->assertNotNull($zone);
    }

    /**
     * Test a failed new Master Zone creation
     */
    public function testNewMasterZoneCreationFailure()
    {
        $zone = factory(Zone::class)->create();

        $this->visit('zones/create')
            ->type($zone->domain, 'domain')
            ->press('master_zone')
            ->see('The domain has already been taken.')
            ->seePageIs('/zones/create');
    }

    /**
     * Test a failed new Slave Zone creation
     */
    public function testNewSlaveZoneCreationFailure()
    {
        $this->visit('zones/create')
            ->type('slave.com', 'domain')
            ->type('280.168.1.3', 'master')
            ->press('slave_zone');

        // Get from DB if Zone has been created.
        $zone = Zone::where('domain', 'slave.com')
            ->first();

        $this->assertNull($zone);
    }

    /**
     * Test a Zone view
     */
    public function testViewZone()
    {
        $zone = factory(Zone::class)->create();

        $this->visit('zones/' . $zone->id)
            ->see($zone->domain);
    }

    /**
     * Test a successful Master Zone edition
     */
    public function testMasterZoneEditionSuccess()
    {
        $originalZone = factory(Zone::class)->create([
            'domain' => 'domain.com',
            'master' => null
        ]);

        $this->visit('zones/' . $originalZone->id . '/edit')
            ->type('modified.com', 'domain')
            ->press('Save data');

        // Get the zone once has been modified
        $modifiedZone = Zone::findOrFail($originalZone->id);

        // Test modified domain field
        $this->assertEquals('modified.com', $modifiedZone->domain);

        // Test field that has not been modified
        $this->assertEquals($originalZone->master, $modifiedZone->master);
    }

    /**
     * Test a successful Slave Zone edition
     */
    public function testSlaveZoneEditionSuccess()
    {
        $originalZone = factory(Zone::class)->create([
            'domain' => 'domain.com',
            'master' => '192.168.1.3'
        ]);

        $this->visit('zones/' . $originalZone->id . '/edit')
            ->type('10.10.10.1', 'master')
            ->press('Save data');

        // Get the zone once has been modified
        $modifiedZone = Zone::findOrFail($originalZone->id);

        // Test modified domain field
        $this->assertEquals('10.10.10.1', $modifiedZone->master);

        // Test field that has not been modified
        $this->assertEquals($originalZone->domain, $modifiedZone->domain);
    }

    /**
     * Test a failed Slave Zone edition
     *
     * Why? Use of an invalid master
     */
    public function testSlaveServerEditionFailure()
    {
        $originalZone = factory(Zone::class)->create([
            'master' => '192.168.1.3'
        ]);

        // Use an Invalid IP Address to fail validation
        $this->visit('zones/' . $originalZone->id . '/edit')
            ->type('280.168.1.2', 'master')
            ->press('Save data');

        // Get the server once has been modified
        $modifiedServer = Zone::findOrFail($originalZone->id);

        // Test fields has not been modified, edit has failed
        $this->assertEquals($originalZone->domain, $modifiedServer->domain);
        $this->assertEquals($originalZone->master, $modifiedServer->master);
    }

    /**
     * Test a successful Zone deletion
     */
    public function testDeleteZoneSuccess()
    {
        $originalZone = factory(Zone::class)->create();

        $this->call('DELETE', 'zones/' . $originalZone->id);

        $this->assertNull(Zone::find($originalZone->id));
    }

    /**
     * Test JSON call listing all Zones
     */
    public function testJSONGetZoneData()
    {
        $originalZone = factory(Zone::class)->create([
            'master' => '192.168.1.3'
        ]);

        $this->json('GET', '/zones/data')
            ->seeJson([
                'domain' => $originalZone->domain,
                'master' => $originalZone->master,
            ]);
    }

}
