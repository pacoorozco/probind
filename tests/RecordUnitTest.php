<?php

use App\Record;
use App\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RecordUnitTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test Record type is upper cased
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
     * Test Record name is lower cased
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
     * Test Record data is lower cased
     */
    public function testDataAttributeIsLowerCased()
    {
        $expectedData = 'testdata';

        $record = new Record([
                'data' => 'testRR',
                'type' => 'CNAME',
                'data' => strtoupper($expectedData),
            ]
        );

        // Attribute must be lower cased
        $this->assertEquals($expectedData, $record->data);
    }

    /**
     * Test Zone relationship
     */
    public function testZoneRelationship()
    {
        $expectedZone = factory(Zone::class)->create();
        $record = factory(Record::class, 'A')->make();
        $expectedZone->records()->save($record);
        $zone = $record->zone;
        $this->assertEquals($expectedZone->domain, $zone->domain);
    }
}
