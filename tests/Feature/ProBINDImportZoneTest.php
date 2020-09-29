<?php

namespace Tests\Feature;

use App\Zone;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;
use Artisan;


class ProBINDImportZoneTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function testCommandWithForwardZoneSuccess()
    {
        $expectedZone = 'domain.com.';

        // Call the command with the created file.
        Artisan::call('probind:import', [
            '--domain' => $expectedZone,
            '--file' => 'tests/testData/forward_zone.txt',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertFalse($zone->reverse_zone);
        $this->assertEquals(10, $zone->records_count);
    }

    public function testCommandWithReverseZoneSuccess()
    {
        $expectedZone = '10.10.in-addr.arpa.';

        // Call the command with the created file.
        Artisan::call('probind:import', [
            '--domain' => $expectedZone,
            '--file' => 'tests/testData/reverse_zone.txt',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertTrue($zone->reverse_zone);
        $this->assertEquals(7, $zone->records_count);
    }

    public function testCommandWithExistingZoneSuccess()
    {
        $expectedZone = factory(Zone::class)->create([
            'domain' => 'domain.com.',
        ]);

        // Call the command with the created file.
        Artisan::call('probind:import', [
            '--domain' => $expectedZone->domain,
            '--file' => 'tests/testData/forward_zone.txt',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone->domain])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertEquals(10, $zone->records_count);
    }

    public function testCommandWithExistingZoneFailure()
    {
        $expectedZone = factory(Zone::class)->create();

        // Call the command with the created file.
        Artisan::call('probind:import', [
            '--domain' => $expectedZone->domain,
            '--file' => 'tests/testData/forward_zone.txt',
            '--force' => false,
        ]);

        $zone = Zone::where(['domain' => $expectedZone->domain])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertNotEquals(10, $zone->records_count);
    }

    public function testCommandFileNotFound()
    {
        $this->expectException(\ErrorException::class);
        // Call the command with the created file.
        Artisan::call('probind:import', [
            '--domain' => 'domain.com',
            '--file' => 'tests/testData/not-existent.txt',
            '--force' => true,
        ]);
    }
}
