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

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    /**
     * Test User username is lower cased.
     */
    public function testUsernameAttributeIsLowerCased()
    {
        $expectedUser = factory(User::class)->make();

        $user = new User();
        $user->username = strtoupper($expectedUser->username);

        // Attribute must be lower cased
        $this->assertEquals($expectedUser->username, $user->username);
    }
}
