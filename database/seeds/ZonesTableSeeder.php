<?php

use App\Record;
use App\Zone;
use Illuminate\Database\Seeder;

class ZonesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Zone::class, 25)
            ->create()
            ->each(function (Zone $zone) {
                $records = factory(Record::class, 'A', 10)->make();
                foreach ($records as $record) {
                    $zone->records()->save($record);
                }
                $records = factory(Record::class, 'CNAME', 2)->make();
                foreach ($records as $record) {
                    $zone->records()->save($record);
                }
            });
    }
}
