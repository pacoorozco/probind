<?php

use App\Server;

class ServerUnitTest extends TestCase
{

    /**
     * Test Server hostname is lower cased
     */
    public function testHostnameAttributeIsLowerCased()
    {
        $expectedHostname = 'server01.local';

        $server = new Server([
                'hostname'   => strtoupper($expectedHostname),
                'ip_address' => '192.168.1.2',
                'type'       => 'master',
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedHostname, $server->hostname);
    }

    /**
     * Test Server type is lower cased
     */
    public function testTypeAttributeIsLowerCased()
    {
        $expectedType = 'master';

        $server = new Server([
                'hostname'   => 'server01.local',
                'ip_address' => '192.168.1.2',
                'type'       => strtoupper($expectedType),
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedType, $server->type);
    }

    /**
     * Test Server type is a valid one
     */
    public function testTypeAttributeIsValid()
    {
        $expectedType = 'InvalidValue';

        $server = new Server([
                'hostname'   => 'server01.local',
                'ip_address' => '192.168.1.2',
                'type'       => $expectedType,
            ]
        );

        // Attribute must be defined as one of Server::$validServerTypes.
        // If is not defined, we assign the first one.
        $this->assertEquals(head(Server::$validServerTypes), $server->type);
    }

    /**
     * Test Server NS record formatting
     */
    public function testGetNSRecord()
    {
        $hostname = 'server01.local';
        $expectedNSRecord = sprintf("%-32s IN\tNS\t%s.", ' ', $hostname);

        $server = new Server([
                'hostname'   => $hostname,
                'ip_address' => '192.168.1.2',
                'type'       => 'master',
            ]
        );

        // Function must return a specified format
        $this->assertEquals($expectedNSRecord, $server->getNSRecord());
    }
}
