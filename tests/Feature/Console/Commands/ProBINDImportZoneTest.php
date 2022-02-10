<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\ProBINDImportZone;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProBINDImportZoneTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_imports_a_forward_zone_successfully()
    {
        $expectedDomain = 'domain.com.';

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/forward_zone.txt',
        ])->assertExitCode(ProBINDImportZone::SUCCESS_CODE);

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
        ])->assertExitCode(ProBINDImportZone::SUCCESS_CODE);

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
        ])->assertExitCode(ProBINDImportZone::ERROR_EXISTING_ZONE_CODE);
    }

    /** @test */
    public function command_fails_when_loading_nonexistent_file()
    {
        // Call the command with the created file.
        $errorCode = Artisan::call('probind:import', [
            '--domain' => 'domain.com',
            '--file' => 'tests/testData/not-existent.txt',
        ]);

        $this->assertEquals(ProBINDImportZone::ERROR_PARSING_FILE_CODE, $errorCode);
    }
}
