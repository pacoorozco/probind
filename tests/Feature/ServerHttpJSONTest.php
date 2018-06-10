<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2018 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author         Paco Orozco <paco@pacoorozco.info>
 * @copyright   2018 Paco Orozco
 * @license         GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link               https://github.com/pacoorozco/probind
 */

namespace Tests\Feature;

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ServerHttpJSONTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Test JSON call listing all Servers
     */
    public function testJSONGetServerData()
    {
        $originalServer = factory(Server::class)->create();

        return $this->json('GET', '/servers/data')
            ->assertStatus(200)
            ->assertJson([
                'hostname' => $originalServer->hostname,
                'ip_address' => $originalServer->ip_address,
            ]);
    }
}
