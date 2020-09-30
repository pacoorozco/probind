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

use App\Helpers\Helper;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * Test Helper::addStatusLabel().
     */
    public function testAddStatusLabel()
    {
        $mapStatusToLabel = [
            '0' => 'false',
            '1' => 'true',
        ];

        // True / False execution.
        $this->assertEquals('it is true', Helper::addStatusLabel(true, 'it is', $mapStatusToLabel));
        $this->assertEquals('it is false', Helper::addStatusLabel(false, 'it is', $mapStatusToLabel));
        // Empty string.
        $this->assertEquals('true', Helper::addStatusLabel(true, '', $mapStatusToLabel));
        // Null string.
        $this->assertEquals('false', Helper::addStatusLabel(false, null, $mapStatusToLabel));
    }
}
