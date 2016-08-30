<?php

use App\Record;
use App\Zone;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchHttpTest extends TestCase
{

    use DatabaseMigrations;

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
}
