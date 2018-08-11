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

namespace Tests\Feature;

use App\Record;
use App\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class RecordHttpTest extends BrowserKitTestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

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
            ->type('testdata', 'data')
            ->press('Save data');

        // Get from DB if Record has been created.
        $record = Record::where('name', 'testrr')
            ->where('ttl', 128)
            ->where('type', 'CNAME')
            ->where('data', 'testdata')
            ->first();

        $this->assertNotNull($record);
    }

    /**
     * Test a successful new Record creation
     */
    /*
     * TODO: It depends on Javascript

    public function testNewSRVRecordCreationSuccess()
    {
        $zone = factory(Zone::class)->create();

        $this->visit('zones/' . $zone->id . '/records/create')
            ->see($zone->domain)
            ->type('testRR', 'name')
            ->type('128', 'ttl')
            ->select('SRV', 'type')
            ->type('10', 'priority')
            ->type('testdata', 'data')
            ->press('Save data');

        // Get from DB if Record has been created.
        $record = Record::where('name', 'testrr')
            ->where('ttl', 128)
            ->where('type', 'SRV')
            ->where('priority', '10')
            ->where('data', 'testdata')
            ->first();

        $this->assertNotNull($record);
    }
    */

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
