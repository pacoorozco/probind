<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ImportZone;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ImportZoneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_imports_a_forward_zone_successfully()
    {
        $expectedDomain = 'domain.com.';

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/forward_zone.txt',
        ])->assertExitCode(ImportZone::SUCCESS_CODE);

        $zone = Zone::where(['domain' => $expectedDomain])
            ->withCount('records')
            ->first();

        $this->assertNotNull($zone);
        $this->assertFalse($zone->reverse_zone);
        $this->assertEquals(9, $zone->records_count);
    }

    /** @test */
    public function command_imports_a_reverse_zone_successfully()
    {
        $expectedDomain = '10.10.in-addr.arpa.';

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/reverse_zone.txt',
        ])->assertExitCode(ImportZone::SUCCESS_CODE);

        $zone = Zone::where(['domain' => $expectedDomain])
            ->withCount('records')
            ->first();

        $this->assertNotNull($zone);
        $this->assertTrue($zone->reverse_zone);
        $this->assertEquals(6, $zone->records_count);
    }

    /** @test */
    public function command_fails_when_the_zone_already_exists()
    {
        $expectedDomain = 'domain.com.';

        Zone::factory()->primary()->create([
            'domain' => $expectedDomain,
        ]);

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/forward_zone.txt',
        ])->assertExitCode(ImportZone::ERROR_EXISTING_ZONE_CODE);
    }

    /** @test */
    public function command_fails_when_loading_nonexistent_file()
    {
        $errorCode = Artisan::call('probind:import', [
            '--domain' => 'domain.com',
            '--file' => 'tests/testData/not-existent.txt',
        ]);

        $this->assertEquals(ImportZone::ERROR_PARSING_FILE_CODE, $errorCode);
    }

    /** @test */
    public function command_fails_when_parameters_are_wrong()
    {
        $errorCode = Artisan::call('probind:import', [
            '--domain' => [],
            '--file' => 'tests/testData/not-existent.txt',
        ]);

        $this->assertEquals(ImportZone::ERROR_INVALID_PARAMETER, $errorCode);

        $errorCode = Artisan::call('probind:import', [
            '--domain' => 'domain.com',
            '--file' => [],
        ]);

        $this->assertEquals(ImportZone::ERROR_INVALID_PARAMETER, $errorCode);
    }

}
