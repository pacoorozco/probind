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

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     * @dataProvider username()
     */
    public function username_is_lowercase(string $testUsername, string $want)
    {
        /** @var User $user */
        $user = User::factory()->make([
            'username' => $testUsername,
        ]);

        $this->assertEquals($want, $user->username);
    }

    public function username(): array
    {
        return [
            'lowercase username' => ['admin', 'admin'],
            'uppercase username' => ['ADMIN', 'admin'],
            'mixed-case username' => ['username.IS.mixed-case', 'username.is.mixed-case'],
        ];
    }
}
