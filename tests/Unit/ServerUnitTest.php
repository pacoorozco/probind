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

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ServerUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Server hostname is lower cased
     */
    public function testHostnameAttributeIsLowerCased()
    {
        $expectedHostname = 'server01.local';

        $server = new Server([
                'hostname' => strtoupper($expectedHostname),
                'ip_address' => '192.168.1.2',
                'type' => 'master',
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedHostname, $server->hostname);
    }

    /**
     * Test Server type is lower cased
     */
    public function testTypeAttributeIsLowerCased()
    {
        $expectedType = 'master';

        $server = new Server([
                'hostname' => 'server01.local',
                'ip_address' => '192.168.1.2',
                'type' => strtoupper($expectedType),
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedType, $server->type);
    }

    /**
     * Test Server type is a valid one with invalid value
     */
    public function testTypeAttributeIsValidWithInvalidValue()
    {
        $expectedType = 'invalid_value';

        $server = new Server([
            'hostname' => 'server01.local',
            'ip_address' => '192.168.1.2',
            'type' => $expectedType,
        ]);

        // Attribute must be defined as one of Server::$validServerTypes.
        $this->assertNotEquals($expectedType, $server->type);
    }

    /**
     * Test Server NS record formatting
     */
    public function testGetNSRecord()
    {
        $hostname = 'server01.local';
        $expectedNSRecord = sprintf("%-32s IN\tNS\t%s.", ' ', $hostname);

        $server = new Server([
                'hostname' => $hostname,
                'ip_address' => '192.168.1.2',
                'type' => 'master',
            ]
        );

        // Function must return a specified format
        $this->assertEquals($expectedNSRecord, $server->getNSRecord());
    }

    /**
     * Test scope withPushCapability()
     */
    public function testScopeWithPushCapability()
    {
        // Create Server items that are out this scope
        factory(Server::class, 5)->create([
            'push_updates' => false
        ]);
        $servers = Server::withPushCapability()->get();

        // Pre-condition.
        $this->assertEmpty($servers);

        // Create Server items that are in this scope
        factory(Server::class, 5)->create([
            'push_updates' => true
        ]);
        $servers = Server::withPushCapability()->get();

        $this->assertCount(5, $servers);
    }

}
