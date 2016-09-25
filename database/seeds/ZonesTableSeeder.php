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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2016 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/probind
 *
 */

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
        factory(Zone::class, 15)
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

        factory(Zone::class, 'reverse', 5)
            ->create();
    }
}
