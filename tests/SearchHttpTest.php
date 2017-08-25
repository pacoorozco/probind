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

use App\Record;
use App\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchHttpTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    public function testSearchWithResults()
    {
        // Prepare data to make tests
        $zone = factory(Zone::class)->create();

        // Create the same 25 A Records for abc.com and xyz.com
        $records = factory(Record::class, 'A', 25)->make();
        $zone->records()->saveMany($records);

        $this->visit('search')
            ->type($zone->domain, 'domain')
            ->select('A', 'type')
            ->press('Search')
            ->dontSee('No results have been found!');
    }

    public function testSearchWithoutResults()
    {
        // Prepare data to make tests
        $zone = factory(Zone::class)->create();

        // Create the same 25 A Records for abc.com and xyz.com
        $records = factory(Record::class, 'A', 25)->make();
        $zone->records()->saveMany($records);

        $this->visit('search')
            ->type($zone->domain, 'domain')
            ->select('CNAME', 'type')
            ->press('Search')
            ->see('No results have been found!');
    }

    public function testSearchWithAllCriteriaFields()
    {
        // Prepare data to make tests
        $zone = factory(Zone::class)->create();

        // Create the same 25 A Records for abc.com and xyz.com
        $record = factory(Record::class, 'A')->make();
        $zone->records()->save($record);

        $this->visit('search')
            ->type($zone->domain, 'domain')
            ->type($record->name, 'name')
            ->select('A', 'type')
            ->type($record->data, 'data')
            ->press('Search')
            ->dontSee('No results have been found!');
    }
}
