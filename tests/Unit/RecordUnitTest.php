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
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Unit;

use App\Record;
use App\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RecordUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Record type is upper cased.
     */
    public function testTypeAttributeIsUpperCased()
    {
        $expectedType = 'CNAME';

        $record = new Record([
                'name' => 'testRR',
                'type' => strtolower($expectedType),
                'data' => 'testData',
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedType, $record->type);
    }

    /**
     * Test Record name is lower cased.
     */
    public function testNameAttributeIsLowerCased()
    {
        $expectedName = 'testrr';

        $record = new Record([
                'name' => strtoupper($expectedName),
                'type' => 'CNAME',
                'data' => 'testData',
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedName, $record->name);
    }

    /**
     * Test Record data is not touched.
     */
    public function testDataAttributeIsNotTouched()
    {
        $expectedData = 'This is a log TXT message.';

        $record = new Record([
                'data' => 'testRR',
                'type' => 'TXT',
                'data' => $expectedData,
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedData, $record->data);
    }

    /**
     * Test Zone relationship.
     */
    public function testZoneRelationship()
    {
        $expectedZone = factory(Zone::class)->create();
        $record = factory(Record::class, 'A')->make();
        $expectedZone->records()->save($record);
        $zone = $record->zone;
        $this->assertEquals($expectedZone->domain, $zone->domain);
    }

    /** @test */
    public function it_formats_A_record_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'A',
            'data' => '192.168.1.2',
        ]);
        $want = 'foo                                      	IN	A	192.168.1.2';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_A_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'A',
            'ttl' => 3600,
            'data' => '192.168.1.2',
        ]);
        $want = 'foo                                      3600	IN	A	192.168.1.2';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_AAAA_record_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'AAAA',
            'data' => '2a01:8840:6::1',
        ]);
        $want = 'foo                                      	IN	AAAA	2a01:8840:6::1';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_AAAA_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'ttl' => 3600,
            'type' => 'AAAA',
            'data' => '2a01:8840:6::1',
        ]);
        $want = 'foo                                      3600	IN	AAAA	2a01:8840:6::1';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_CNAME_record_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'CNAME',
            'data' => 'bar',
        ]);
        $want = 'foo                                      	IN	CNAME	bar';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_CNAME_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'ttl' => 3600,
            'type' => 'CNAME',
            'data' => 'bar',
        ]);
        $want = 'foo                                      3600	IN	CNAME	bar';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_MX_record_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'MX',
            'priority' => 20,
            'data' => 'server.baz.com',
        ]);
        $want = 'foo                                      	IN	MX	20 server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_MX_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'MX',
            'ttl' => 3600,
            'priority' => 20,
            'data' => 'server.baz.com',
        ]);
        $want = 'foo                                      3600	IN	MX	20 server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_NS_record_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'type' => 'NS',
            'data' => 'server.baz.com',
        ]);
        $want = 'foo                                      	IN	NS	server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_NS_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => 'foo',
            'ttl' => 3600,
            'type' => 'NS',
            'data' => 'server.baz.com',
        ]);
        $want = 'foo                                      3600	IN	NS	server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_PTR_record_properly(): void
    {
        $record = new Record([
            'name' => '99',
            'type' => 'PTR',
            'data' => 'server.baz.com',
        ]);
        $want = '99                                       	IN	PTR	server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_PTR_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => '99',
            'ttl' => 3600,
            'type' => 'PTR',
            'data' => 'server.baz.com',
        ]);
        $want = '99                                       3600	IN	PTR	server.baz.com';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_SRV_record_properly(): void
    {
        $record = new Record([
            'name' => '_kerberos._tcp',
            'type' => 'SRV',
            'priority' => 10,
            'data' => '100 88 server.baz.com.',
        ]);
        $want = '_kerberos._tcp                           	IN	SRV	10 100 88 server.baz.com.';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_SRV_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => '_kerberos._tcp',
            'ttl' => 3600,
            'type' => 'SRV',
            'priority' => 10,
            'data' => '100 88 server.baz.com.',
        ]);
        $want = '_kerberos._tcp                           3600	IN	SRV	10 100 88 server.baz.com.';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_TXT_record_properly(): void
    {
        $record = new Record([
            'name' => '',
            'type' => 'TXT',
            'data' => '"v=spf1 -all"',
        ]);
        $want = '                                         	IN	TXT	"v=spf1 -all"';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_TXT_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => '',
            'ttl' => 3600,
            'type' => 'TXT',
            'data' => '"v=spf1 -all"',
        ]);
        $want = '                                         3600	IN	TXT	"v=spf1 -all"';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_NAPTR_record_properly(): void
    {
        $record = new Record([
            'name' => '',
            'type' => 'NAPTR',
            'priority' => 100,
            'data' => '10 "S" "SIP+D2U" "!^.*$!sip:customer-service@example.com!" _sip._udp.example.com.',
        ]);
        $want = '                                         	IN	NAPTR	100 10 "S" "SIP+D2U" "!^.*$!sip:customer-service@example.com!" _sip._udp.example.com.';

        $this->assertEquals($want, $record->formatResourceRecord());
    }

    /** @test */
    public function it_formats_NAPTR_record_with_custom_TTL_properly(): void
    {
        $record = new Record([
            'name' => '',
            'ttl' => 3600,
            'type' => 'NAPTR',
            'priority' => 100,
            'data' => '10 "S" "SIP+D2U" "!^.*$!sip:customer-service@example.com!" _sip._udp.example.com.',
        ]);
        $want = '                                         3600	IN	NAPTR	100 10 "S" "SIP+D2U" "!^.*$!sip:customer-service@example.com!" _sip._udp.example.com.';

        $this->assertEquals($want, $record->formatResourceRecord());
    }
}
