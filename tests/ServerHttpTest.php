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

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServerHttpTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Test a successful new Server creation
     */
    public function testNewServerCreationSuccess()
    {
        $this->visit('servers/create')
            ->type('server01.local', 'hostname')
            ->type('192.168.1.2', 'ip_address')
            ->select('slave', 'type')
            ->select('0', 'ns_record')
            ->select('1', 'push_updates')
            ->press('Save data')
            ->seePageIs('/servers');

        // Get from DB if Server has been created.
        $server = Server::where('hostname', 'server01.local')
            ->where('ip_address', '192.168.1.2')
            ->where('type', 'slave')
            ->where('ns_record', false)
            ->where('push_updates', true)
            ->first();

        $this->assertNotNull($server);
    }

    /**
     * Test a failed new Server creation
     *
     * Why? Use of an invalid ip_address
     */
    public function testNewServerCreationFailure()
    {
        // Use an Invalid IP Address to fail validation
        $this->visit('servers/create')
            ->type('server01.local', 'hostname')
            ->type('280.168.1.2', 'ip_address')
            ->select('master', 'type')
            ->select('1', 'ns_record')
            ->press('Save data')
            ->seePageIs('/servers/create');

        // Get from DB, Server has not been created.
        $server = Server::where('hostname', 'server01.local')->first();
        $this->assertNull($server);
    }

    /**
     * Test a Server view
     */
    public function testViewServer()
    {
        $server = factory(Server::class)->create();

        $this->visit('servers/' . $server->id)
            ->see($server->hostname)
            ->see($server->ip_address)
            ->see($server->type);
    }

    /**
     * Test a successful Server edition
     */
    public function testServerEditionSuccess()
    {
        $originalServer = factory(Server::class)->create([
            'hostname'  => 'server01.local',
            'ns_record' => false
        ]);

        // Modify hostname and ns_record
        $this->visit('servers/' . $originalServer->id . '/edit')
            ->type('server02.local', 'hostname')
            ->select('1', 'ns_record')
            ->press('Save data');

        // Get the server once has been modified
        $modifiedServer = Server::findOrFail($originalServer->id);

        // Test modified hostname and ns_record field
        $this->assertEquals('server02.local', $modifiedServer->hostname);
        $this->assertEquals(1, $modifiedServer->ns_record);

        // Test field that has not been modified
        $this->assertEquals($originalServer->ip_address, $modifiedServer->ip_address);
    }

    /**
     * Test a failed Server edition
     *
     * Why? Use of an invalid ip_address
     */
    public function testServerEditionFailure()
    {
        $originalServer = factory(Server::class)->create([
            'hostname'  => 'server01.local',
            'ns_record' => false
        ]);

        // Use an Invalid IP Address to fail validation
        $this->visit('servers/' . $originalServer->id . '/edit')
            ->type('server02.local', 'hostname')
            ->type('280.168.1.2', 'ip_address')
            ->press('Save data');

        // Get the server once has been modified
        $modifiedServer = Server::findOrFail($originalServer->id);

        // Test fields has not been modified, edit has failed
        $this->assertEquals($originalServer->hostname, $modifiedServer->hostname);
        $this->assertEquals($originalServer->ip_address, $modifiedServer->ip_address);
    }

    /**
     * Test a successful Server deletion
     */
    public function testDeleteServerSuccess()
    {
        $originalServer = factory(Server::class)->create();

        $this->visit('servers/' . $originalServer->id . '/delete')
            ->press('Delete');

        $this->assertNull(Server::find($originalServer->id));
    }

    /**
     * Test JSON call listing all Servers
     */
    public function testJSONGetServerData()
    {
        $originalServer = factory(Server::class)->create();

        $this->json('GET', '/servers/data')
            ->seeJson([
                'hostname'   => $originalServer->hostname,
                'ip_address' => $originalServer->ip_address,
            ]);
    }
}
