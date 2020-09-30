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

namespace Tests\Feature;

use App\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class ZoneHttpTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Data Set for Primary Zone Creation.
     *
     * @return array
     */
    public function primaryZoneCreationWithCustomSettingsDataSet(): array
    {
        return [
            // 'name of the test case' => ['domain', 'refresh', 'retry', 'expire', 'negative_ttl', 'default_ttl', 'expected']
            'valid data' => ['domain.com.', '10', '11', '12', '13', '14', true],
            'with invalid refresh' => ['domain.com.', 'foo', '11', '12', '13', '14', false],
            'with invalid retry' => ['domain.com.', '10', 'foo', '12', '13', '14', false],
            'with invalid expire' => ['domain.com.', '10', '11', 'foo', '13', '14', false],
            'with invalid negative ttl' => ['domain.com.', '10', '11', '12', 'foo', '14', false],
            'with invalid default ttl' => ['domain.com.', '10', '11', '12', '13', 'foo', false],
        ];
    }

    /**
     * Test a successful new Master Zone creation.
     *
     * @test
     * @dataProvider primaryZoneCreationWithCustomSettingsDataSet
     *
     * @param string $domain
     * @param string $refresh
     * @param string $retry
     * @param string $expire
     * @param string $negative_ttl
     * @param string $default_ttl
     * @param bool $expected
     */
    public function new_master_zone_creation_with_custom_settings(
        string $domain,
        string $refresh,
        string $retry,
        string $expire,
        string $negative_ttl,
        string $default_ttl,
        bool $expected
    ): void {
        $this->visit('zones/create')
            ->type($domain, 'domain')
            ->check('custom_settings')
            ->type($refresh, 'refresh')
            ->type($retry, 'retry')
            ->type($expire, 'expire')
            ->type($negative_ttl, 'negative_ttl')
            ->type($default_ttl, 'default_ttl')
            ->press('master_zone');

        // Get from DB if Zone has been created.
        $got = Zone::where('domain', $domain)
            ->where('refresh', $refresh)
            ->where('retry', $retry)
            ->where('expire', $expire)
            ->where('negative_ttl', $negative_ttl)
            ->where('default_ttl', $default_ttl)
            ->first();

        if (true === $expected) {
            $this->assertNotNull($got);
        } else {
            $this->assertNull($got);
        }
    }

    /**
     * Data Set for Primary Zone Creation.
     *
     * @return array
     */
    public function primaryZoneCreationWithoutCustomSettingsDataSet(): array
    {
        return [
            // 'name of the test case' => ['domain', 'expected']
            'valid data' => ['domain.com.', false, '', '', '', '', '', true],
            'with invalid zone name' => ['domain.com', false, '', '', '', '', '', false],
        ];
    }

    /**
     * Test a successful new Master Zone creation.
     *
     * @test
     * @dataProvider primaryZoneCreationWithoutCustomSettingsDataSet
     *
     * @param string $domain
     * @param bool $expected
     */
    public function new_master_zone_creation_without_custom_settings(
        string $domain,
        bool $expected
    ): void {
        $this->visit('zones/create')
            ->type($domain, 'domain')
            ->press('master_zone');

        // Get from DB if Zone has been created.
        $got = Zone::where('domain', $domain)
            ->first();

        if (true === $expected) {
            $this->assertNotNull($got);
        } else {
            $this->assertNull($got);
        }
    }

    /**
     * Test a successful new Slave Zone creation.
     */
    public function testNewSlaveZoneCreationSuccess()
    {
        $this->visit('zones/create')
            ->type('slave.com.', 'domain')
            ->type('192.168.1.3', 'master_server')
            ->press('slave_zone');

        // Get from DB if Zone has been created.
        $zone = Zone::where('domain', 'slave.com.')
            ->where('master_server', '192.168.1.3')
            ->first();

        $this->assertNotNull($zone);
    }

    /**
     * Test a failed new Master Zone creation.
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
     * Test a failed new Slave Zone creation.
     */
    public function testNewSlaveZoneCreationFailure()
    {
        $this->visit('zones/create')
            ->type('slave.com', 'domain')
            ->type('280.168.1.3', 'master_server')
            ->press('slave_zone');

        // Get from DB if Zone has been created.
        $zone = Zone::where('domain', 'slave.com')
            ->first();

        $this->assertNull($zone);
    }

    /**
     * Test a Zone view.
     */
    public function testViewZone()
    {
        $zone = factory(Zone::class)->create();

        $this->visit('zones/' . $zone->id)
            ->see($zone->domain);
    }

    /**
     * Test a successful Master Zone edition.
     */
    public function testMasterZoneEditionSuccess()
    {
        $originalZone = factory(Zone::class)->create([
            'domain' => 'domain.com',
            'master_server' => null,
        ]);

        $this->visit('zones/' . $originalZone->id . '/edit')
            ->check('#custom_settings')
            ->type(7200, '#refresh')
            ->type(7200, '#retry')
            ->type(7200, '#expire')
            ->type(7200, '#negative_ttl')
            ->type(7200, '#default_ttl')
            ->press('Save data');

        // Get the zone once has been modified
        $modifiedZone = Zone::findOrFail($originalZone->id);

        // Test modified domain field
        $this->assertEquals(7200, $modifiedZone->refresh);
        $this->assertEquals(7200, $modifiedZone->retry);
        $this->assertEquals(7200, $modifiedZone->expire);

        // Test field that has not been modified
        $this->assertEquals($originalZone->master_server, $modifiedZone->master_server);
    }

    /**
     * Test a successful Slave Zone edition.
     */
    public function testSlaveZoneEditionSuccess()
    {
        $originalZone = factory(Zone::class)->create([
            'domain' => 'domain.com',
            'master_server' => '192.168.1.3',
        ]);

        $this->visit('zones/' . $originalZone->id . '/edit')
            ->type('10.10.10.1', 'master_server')
            ->press('Save data');

        // Get the zone once has been modified
        $modifiedZone = Zone::findOrFail($originalZone->id);

        // Test modified domain field
        $this->assertEquals('10.10.10.1', $modifiedZone->master_server);

        // Test field that has not been modified
        $this->assertEquals($originalZone->domain, $modifiedZone->domain);
    }

    /**
     * Test a failed Slave Zone edition.
     *
     * Why? Use of an invalid master
     */
    public function testSlaveServerEditionFailure()
    {
        $originalZone = factory(Zone::class)->create([
            'master_server' => '192.168.1.3',
        ]);

        // Use an Invalid IP Address to fail validation
        $this->visit('zones/' . $originalZone->id . '/edit')
            ->type('280.168.1.2', 'master_server')
            ->press('Save data');

        // Get the server once has been modified
        $modifiedServer = Zone::findOrFail($originalZone->id);

        // Test fields has not been modified, edit has failed
        $this->assertEquals($originalZone->domain, $modifiedServer->domain);
        $this->assertEquals($originalZone->master_server, $modifiedServer->master_server);
    }

    /**
     * Test a successful Zone deletion.
     */
    public function testDeleteZoneSuccess()
    {
        $originalZone = factory(Zone::class)->create();

        $this->call('DELETE', 'zones/' . $originalZone->id);

        $this->assertNull(Zone::find($originalZone->id));
    }

    /**
     * Test JSON call listing all Zones.
     */
    public function testJSONGetZoneData()
    {
        $originalZone = factory(Zone::class)->create([
            'master_server' => '192.168.1.3',
        ]);

        $this->json('GET', '/zones/data')
            ->seeJson([
                'domain' => $originalZone->domain,
                'master_server' => $originalZone->master_server,
            ]);
    }
}
