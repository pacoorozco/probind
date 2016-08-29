<?php

use App\Record;
use App\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RecordHttpTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test a successful new Record creation
     */
    public function testNewRecordCreationSuccess()
    {
        $zone = factory(Zone::class)->create();

        $this->visit('zones/' . $zone->id . '/records/create')
            ->see($zone->domain)
            ->type('testRR', 'name')
            ->type('128', 'ttl')
            ->select('CNAME', 'type')
            ->type('testData', 'data')
            ->press('Save data')
            ->see('Record created successfully.')
            ->seePageIs('zones/' . $zone->id . '/records');
    }

    /**
     * Test a Record view
     */
    public function testViewRecord()
    {
        $zone = factory(Zone::class)->create();
        $record = factory(Record::class, 'CNAME')->make();
        $zone->records()->save($record);

        $this->visit('zones/' . $zone->id . '/records/' . $record->id)
            ->see($record->name)
            ->see($record->data);
    }

    /**
     * Test a successful Record edition
     */
    public function testRecordEditionSuccess()
    {
        $zone = factory(Zone::class)->create();
        $originalRecord = factory(Record::class, 'CNAME')->make([
            'name' => 'test-rr'
        ]);
        $zone->records()->save($originalRecord);

        $this->visit('zones/' . $zone->id . '/records/' . $originalRecord->id . '/edit')
            ->type('modified-rr', 'name')
            ->press('Save data');

        // Get the zone once has been modified
        $modifiedRecord = Record::findOrFail($originalRecord->id);

        // Test modified name field
        $this->assertEquals('modified-rr', $modifiedRecord->name);

        // Test field that has not been modified
        $this->assertEquals($originalRecord->data, $modifiedRecord->data);
    }

    /**
     * Test a successful Record deletion
     */
    public function testDeleteRecordSuccess()
    {
        $zone = factory(Zone::class)->create();
        $originalRecord = factory(Record::class, 'CNAME')->make();
        $zone->records()->save($originalRecord);

        $this->visit('zones/' . $zone->id . '/records/' . $originalRecord->id . '/delete')
            ->press('Delete');

        $this->assertNull(Record::find($originalRecord->id));
    }

    /**
     * Test JSON call listing all Records for a Zone
     */
    public function testJSONGetRecordData()
    {
        $zone = factory(Zone::class)->create();
        $originalRecord = factory(Record::class, 'CNAME')->make();
        $zone->records()->save($originalRecord);

        $this->json('GET', '/zones/' . $zone->id . '/records/data')
            ->seeJson([
                'name' => $originalRecord->name,
                'data' => $originalRecord->data,
            ]);
    }
}
