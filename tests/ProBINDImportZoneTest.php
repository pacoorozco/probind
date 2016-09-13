<?php

use App\Zone;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ProBINDImportZoneTest extends TestCase
{
    use DatabaseMigrations;

    protected $fileContents = '
; This is a comment
$TTL 172800
@               IN      SOA     dns1.domain.com. hostmaster.domain.col. (
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

    private $tempDir;

    public function setUp()
    {
        parent::setUp();

        $this->tempDir = __DIR__ . '/tmp';
        mkdir($this->tempDir);
        file_put_contents($this->tempDir . '/domain.com.db', $this->fileContents);
    }

    public function tearDown()
    {
        $files = new Filesystem();
        $files->deleteDirectory($this->tempDir);

        parent::tearDown();
    }

    public function testCommandSuccess()
    {
        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone'     => 'domain.com',
            'zonefile' => $this->tempDir . '/domain.com.db',
            '--force'  => true,
        ]);

        $zone = Zone::where(['domain' => 'domain.com'])->first();

        $this->assertNotNull($zone);
        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertCount(9, $zone->records);
    }

    /**
     * @expectedException \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testCommandFileNotFound()
    {
        // Call the command with the created file.
        Artisan::call('probind:import', [
            'zone'     => 'domain.com',
            'zonefile' => $this->tempDir . '/not.exist.db',
            '--force'  => true,
        ]);
    }
}
