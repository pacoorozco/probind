<?php

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
        factory(App\Zone::class, 25)
            ->create()
            ->each(function($zone) {
                $records = factory(App\Record::class, 'A', 10)->make();
                foreach ($records as $record) {
                    $zone->records()->save($record);
                }
                $records = factory(App\Record::class, 'CNAME', 2)->make();
                foreach ($records as $record) {
                    $zone->records()->save($record);
                }
            });
    }
}
