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
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Feature\Models;

use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_zones_with_pending_changes()
    {
        // Zones without pending changes
        Zone::factory()->count(2)->create([
            'has_modifications' => false,
        ]);

        // Zones with pending changes
        Zone::factory()->count(3)->create([
            'has_modifications' => true,
        ]);

        $this->assertCount(3, Zone::withPendingChanges()->get());
    }

    /** @test */
    public function it_returns_all_primary_zones()
    {
        Zone::factory()->count(2)->secondary()->create();
        Zone::factory()->count(3)->primary()->create();

        $this->assertCount(3, Zone::primaryZones()->get());
    }
}
