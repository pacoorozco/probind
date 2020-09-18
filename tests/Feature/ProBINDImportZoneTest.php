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

    protected $forwardZoneFileContents = '
; This is a comment
$TTL 172800
@               IN      SOA     dns1.domain.com. hostmaster.domain.com. (
                                        2016080401      ; serial (aaaammddvv)
                                        86400           ; Refresh
                                        7200            ; Retry
                                        3628800         ; Expire
                                        7200 )  ; Minimum TTL
                                IN      NS      dns1.domain.com.
                                IN      NS      dns2.domain.com.
@                                               IN      MX      10 10.10.10.1
@                                       7200    IN      MX      20 10.10.10.2

ftp                                     7200    IN      A       10.10.10.3
www                                     7200    IN      CNAME   webserver.domain.com.
; webserver.domain.com.                   7200    IN      A       10.10.10.4

$ORIGIN subdomain
www1                                    7200    IN      A       10.10.10.10
                                                IN      A       10.10.10.11
text                                    7200    IN      TXT     "Somewhere over the rainbow"
';

    protected $reverseZoneFileContents = '
; This is a comment
$TTL 172800
@               IN      SOA     dns1.domain.com. hostmaster.domain.com. (
                                        2016080401      ; serial (aaaammddvv)
                                        86400           ; Refresh
                                        7200            ; Retry
                                        3628800         ; Expire
                                        7200 )  ; Minimum TTL
                                IN      NS      dns1.domain.com.
                                IN      NS      dns2.domain.com.

1.10                                    7200    IN      PTR     webserver1.domain.com.
2.10                                            IN      PTR     webserver2.domain.com.
; 3.10                                    7200    IN      PTR     webserver3.domain.com.

20                                      7200    IN      NS      dns-ext.domain.com.
30                                              IN      NS      dns-ext.domain.com.
';

    private $tempDir;

    public function setUp(): void
    {
        parent::setUp();

        $this->tempDir = __DIR__ . '/tmp';
        if (!file_exists($this->tempDir)) {
            mkdir($this->tempDir, 0700, true);
        }
        file_put_contents($this->tempDir . '/forward-zone.db', $this->forwardZoneFileContents);
        file_put_contents($this->tempDir . '/reverse-zone.db', $this->reverseZoneFileContents);
    }

    public function tearDown(): void
    {
        $files = new Filesystem();
        $files->deleteDirectory($this->tempDir);

        parent::tearDown();
    }

    public function testCommandWithForwardZoneSuccess()
    {
        $expectedZone = 'domain.com';

        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone' => $expectedZone,
            'zonefile' => $this->tempDir . '/forward-zone.db',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertFalse($zone->reverse_zone);
        $this->assertCount(9, $zone->records);
    }

    public function testCommandWithReverseZoneSuccess()
    {
        $expectedZone = '10.10.in-addr.arpa';

        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone' => $expectedZone,
            'zonefile' => $this->tempDir . '/reverse-zone.db',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertTrue($zone->reverse_zone);
        $this->assertCount(6, $zone->records);
    }

    public function testCommandWithExistingZoneSuccess()
    {
        $expectedZone = factory(Zone::class)->create();

        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone' => $expectedZone->domain,
            'zonefile' => $this->tempDir . '/forward-zone.db',
            '--force' => true,
        ]);

        $zone = Zone::where(['domain' => $expectedZone->domain])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertCount(9, $zone->records);
    }

    public function testCommandWithExistingZoneFailure()
    {
        $expectedZone = factory(Zone::class)->create();

        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone' => $expectedZone->domain,
            'zonefile' => $this->tempDir . '/forward-zone.db',
            '--force' => false,
        ]);

        $zone = Zone::where(['domain' => $expectedZone->domain])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertNotCount(9, $zone->records);
    }

    /**
     * @expectedException \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testCommandFileNotFound()
    {
        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone' => 'domain.com',
            'zonefile' => $this->tempDir . '/not.exist.db',
            '--force' => true,
        ]);
    }
}
