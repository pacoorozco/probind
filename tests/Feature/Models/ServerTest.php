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

namespace Tests\Feature\Models;

use App\Models\Server;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scoped_query_returns_servers_with_push_capability()
    {
        // Two servers with push capability and active
        Server::factory()->count(2)->create([
            'push_updates' => true,
            'active' => true,
        ]);

        // Other servers out of the scope
        Server::factory()->count(2)->create([
            'push_updates' => false,
        ]);
        Server::factory()->count(2)->create([
            'push_updates' => true,
            'active' => false,
        ]);

        $this->assertCount(2, Server::withPushCapability()->get());
    }
}
