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

class ToolsHttpTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Test Bulk Update feature.
     */
    public function testDoBulkUpdate()
    {
        // Create some zones to test
        factory(Zone::class, 5)->create([
            'has_modifications' => false
        ]);

        // Visit URL to do bulk update
        $this->visit('/tools/update')
            ->press('Bulk update');

        // Get all zones
        $zones = Zone::all();
        foreach ($zones as $zone) {
            $this->assertTrue($zone->hasPendingChanges());
        }
    }
}
