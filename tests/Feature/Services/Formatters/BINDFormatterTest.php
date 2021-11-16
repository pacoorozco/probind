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

namespace Tests\Feature\Services\Formatters;

use App\Models\Zone;
use App\Services\Formatters\BINDFormatter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BINDFormatterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_the_deleted_zones_file_content()
    {
        $zones = Zone::factory()->count(3)->primary()->create();

        $content = BINDFormatter::deletedZones($zones);

        // Get the lines of the content
        $lines = explode(PHP_EOL, $content);

        $this->assertCount(3, $lines);

        foreach ($zones as $zone)
        {
            $this->assertStringContainsString($zone->domain, $content);
        }
    }

    /** @test */
    public function it_returns_empty_deleted_zones_file_content_when_zones_are_empty()
    {
        $zones = new Collection();

        $content = BINDFormatter::deletedZones($zones);

        $this->assertEmpty($content);
    }
}
