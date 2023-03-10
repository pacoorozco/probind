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

namespace Tests\Unit\Models;

use App\Models\ResourceRecord;
use Tests\TestCase;

class ResourceRecordTest extends TestCase
{
    /**
     * @test
     * @dataProvider providesNames()
     */
    public function name_is_lowercase(string $testName, string $want): void
    {
        /** @var ResourceRecord $rr */
        $rr = ResourceRecord::factory()->make([
            'name' => $testName,
        ]);

        $this->assertEquals($want, $rr->name);
    }

    public static function providesNames(): array
    {
        return [
            'lowercase name' => ['lowercase-name', 'lowercase-name'],
            'uppercase name' => ['UPPERCASE-NAME', 'uppercase-name'],
            'mixed-case name' => ['name-IS-mixed-CASE', 'name-is-mixed-case'],
        ];
    }
}
