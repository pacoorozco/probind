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
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Database\Seeders;

use App\Models\ResourceRecord;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZonesTableSeeder extends Seeder
{
    public function run(): void
    {
        Zone::factory()->count(3)->primary()
            ->has(ResourceRecord::factory()->asARecord()->count(10), 'records')
            ->has(ResourceRecord::factory()->asCNAMERecord()->count(2), 'records')
            ->create();

        Zone::factory()->count(2)->secondary()->create();

        Zone::factory()->count(2)->reverse()->create();
    }
}
