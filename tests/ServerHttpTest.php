<?php

use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ServerHttpTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test a successful new Server creation
     */
    public function testNewServerCreationSuccess()
    {
        $this->visit('servers/create')
            ->type('server01.local', 'hostname')
            ->type('192.168.1.2', 'ip_address')
            ->select('slave', 'type')
            ->check('ns_record')
            ->press('Save data')
            ->see('Success')
            ->seePageIs('/servers');
    }

    /**
     * Test a failed new Server creation
     *
     * Why? Use of an invalid ip_address
     */
    public function testNewServerCreationFailure()
    {
        // Use an Invalid IP Address to fail validation
        $this->visit('servers/create')
            ->type('server01.local', 'hostname')
            ->type('280.168.1.2', 'ip_address')
            ->select('master', 'type')
            ->check('ns_record')
            ->press('Save data')
            ->see('The ip address must be a valid IP address.')
            ->seePageIs('/servers/create');
    }

    /**
     * Test a Server view
     */
    public function testViewServer()
    {
        $server = factory(Server::class)->create();

        $this->visit('servers/' . $server->id)
            ->see($server->hostname)
            ->see($server->ip_address)
            ->see($server->type);
    }

    /**
     * Test a successful Server edition
     */
    public function testEditServerCreationSuccess()
    {
        $originalServer = factory(Server::class)->create();

        $this->visit('servers/' . $originalServer->id . '/edit')
            ->type('server01.local', 'hostname')
            ->check('push_updates')
            ->press('Save data');

        // Get the server once has been modified
        $modifiedServer = Server::findOrFail($originalServer->id);

        // Test modified hostname field
        $this->assertEquals('server01.local', $modifiedServer->hostname);

        // Test field that has not been modified
        $this->assertEquals($originalServer->ip_address, $modifiedServer->ip_address);
    }

    /**
     * Test a failed Server edition
     *
     * Why? Use of an invalid ip_address
     */
    public function testEditServerCreationFailure()
    {
        $originalServer = factory(Server::class)->create();

        // Use an Invalid IP Address to fail validation
        $this->visit('servers/' . $originalServer->id . '/edit')
            ->type('280.168.1.2', 'ip_address')
            ->press('Save data')
            ->see('The ip address must be a valid IP address.');

        // Get the server once has been modified
        $modifiedServer = Server::findOrFail($originalServer->id);

        // Test fields has not been modified, edit has failed
        $this->assertEquals($originalServer->hostname, $modifiedServer->hostname);
        $this->assertEquals($originalServer->ip_address, $modifiedServer->ip_address);
    }

    /**
     * Test a successful Server deletion
     */
    public function testDeleteServerSuccess()
    {
        $originalServer = factory(Server::class)->create();

        $this->visit('servers/' . $originalServer->id . '/delete')
            ->press('Delete');

        $this->assertNull(Server::find($originalServer->id));
    }

    /**
     * Test JSON call listing all Servers
     */
    public function testJSONGetServerData()
    {
        $originalServer = factory(Server::class)->create();

        $this->json('GET', '/servers/data')
            ->seeJson([
                'hostname'   => $originalServer->hostname,
                'ip_address' => $originalServer->ip_address,
            ]);
    }
}
