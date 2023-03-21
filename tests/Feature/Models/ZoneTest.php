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

use App\Enums\ResourceRecordType;
use App\Models\ResourceRecord;
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

    /** @test */
    public function it_returns_zero_when_there_are_not_records_created()
    {
        $zone = Zone::factory()->create();

        $this->assertEquals(0, $zone->recordsCount());
    }

    /** @test */
    public function it_returns_number_of_resource_records()
    {
        $zone = Zone::factory()->create();

        ResourceRecord::factory()
            ->count(3)
            ->for($zone)
            ->create();

        $this->assertEquals(3, $zone->recordsCount());
    }

    /** @test */
    public function it_set_pending_changes()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->create([
            'has_modifications' => false,
        ]);

        $zone->setPendingChanges();

        $this->assertTrue($zone->has_modifications);
    }

    /** @test */
    public function it_unset_pending_changes()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->create([
            'has_modifications' => true,
        ]);

        $zone->unsetPendingChanges();

        $this->assertFalse($zone->has_modifications);
    }

    /** @test */
    public function it_should_return_a_default_record_type_for_a_reverse_zone()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->reverse()->create();

        $this->assertEquals('PTR', $zone->getDefaultRecordType());
    }

    /** @test */
    public function it_should_return_a_default_record_type_for_a_forward_zone()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->create();

        $this->assertEquals('A', $zone->getDefaultRecordType());
    }

    /** @test */
    public function it_should_return_valid_record_types_for_a_reverse_zone()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->reverse()->create();

        $this->assertEquals(ResourceRecordType::asArrayForReverseZone(), $zone->getValidRecordTypesForThisZone());
    }

    /** @test */
    public function it_should_return_valid_record_types_for_a_forward_zone()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->create();

        $this->assertEquals(ResourceRecordType::asArrayForForwardZone(), $zone->getValidRecordTypesForThisZone());
    }

    /** @test */
    public function it_should_increase_serial_number_when_force_parameter_is_used()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->withoutPendingChanges()->create();

        $beforeSerial = $zone->serial;

        $zone->increaseSerialNumber(true);

        $zone->refresh();

        $this->assertNotEquals($beforeSerial, $zone->serial);
    }

    /** @test */
    public function it_should_increase_serial_number_when_it_has_pending_changes()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->withPendingChanges()->create();

        $beforeSerial = $zone->serial;

        $zone->increaseSerialNumber();

        $zone->refresh();

        $this->assertNotEquals($beforeSerial, $zone->serial);
    }

    /** @test */
    public function it_should_not_increase_serial_number_when_it_has_not_pending_changes()
    {
        /** @var Zone $zone */
        $zone = Zone::factory()->withoutPendingChanges()->create();

        $beforeSerial = $zone->serial;

        $zone->increaseSerialNumber();

        $zone->refresh();

        $this->assertEquals($beforeSerial, $zone->serial);
    }
}
