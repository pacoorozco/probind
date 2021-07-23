<?php

namespace Tests\Feature;

use App\Console\Commands\ProBINDImportZone;
use App\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProBINDImportZoneTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandWithForwardZoneSuccess()
    {
        $expectedDomain = 'domain.com.';

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/forward_zone.txt',
        ])->assertExitCode(ProBINDImportZone::SUCCESS_CODE);

        $zone = Zone::where(['domain' => $expectedDomain])->first();

        $this->assertNotNull($zone);
        $this->assertFalse($zone->reverse_zone);
        $this->assertEquals(10, $zone->records_count);
    }

    public function testCommandWithReverseZoneSuccess()
    {
        $expectedDomain = '10.10.in-addr.arpa.';

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/reverse_zone.txt',
        ])->assertExitCode(ProBINDImportZone::SUCCESS_CODE);

        $zone = Zone::where(['domain' => $expectedDomain])->first();

        $this->assertNotNull($zone);
        $this->assertTrue($zone->reverse_zone);
        $this->assertEquals(7, $zone->records_count);
    }

    public function testCommandWithExistingZoneFailure()
    {
        $expectedDomain = 'domain.com.';

        /** @var Zone $expectedZone */
        $expectedZone = factory(Zone::class)->create([
            'domain' => $expectedDomain,
        ]);

        $this->artisan('probind:import', [
            '--domain' => $expectedDomain,
            '--file' => 'tests/testData/forward_zone.txt',
        ])->assertExitCode(ProBINDImportZone::ERROR_EXISTING_ZONE_CODE);
    }

    public function testCommandFileNotFound()
    {
        // Call the command with the created file.
        $errorCode = Artisan::call('probind:import', [
            '--domain' => 'domain.com',
            '--file' => 'tests/testData/not-existent.txt',
        ]);

        $this->assertEquals(ProBINDImportZone::ERROR_PARSING_FILE_CODE, $errorCode);
    }
}
