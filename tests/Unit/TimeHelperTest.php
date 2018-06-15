<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2018 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author         Paco Orozco <paco@pacoorozco.info>
 * @copyright   2018 Paco Orozco
 * @license         GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link               https://github.com/pacoorozco/probind
 */

namespace Tests\Unit;

use App\Helpers\TimeHelper;
use Tests\TestCase;

class TimeHelperTest extends TestCase
{
    /**
     * Test static parseToSeconds() function.
     */
    public function testParseToSeconds()
    {
        $testTimeTranslations = [
            '7200' => 7200,
            '10800S' => 10800 * 1,
            '15m' => 15 * 60,
            '3W12h' => 3 * 7 * 24 * 60 * 60 + 12 * 60 * 60,
        ];

        foreach (array_keys($testTimeTranslations) as $time) {
            $seconds = TimeHelper::parseToSeconds($time);
            $this->assertEquals($testTimeTranslations[$time], $seconds);
        }
    }
}
