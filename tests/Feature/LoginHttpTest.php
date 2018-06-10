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
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTestCase;

class LoginHttpTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /**
     * Test a redirect to Login page
     */
    public function testUnAuthenticatedRedirection()
    {
        $this->visit('/')
            ->seePageIs('/login');
    }

    /**
     * Test successful Login
     */
    public function testLoginSuccess()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('VeryS3cr3t'),
        ]);

        $this->visit('/login')
            ->type($user->username, 'username')
            ->type('VeryS3cr3t', 'password')
            ->press('submit')
            ->seePageIs('/');
    }

    /**
     * Test failed Login
     */
    public function testLoginFailure()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('VeryS3cr3t'),
        ]);

        $this->visit('/login')
            ->type($user->username, 'username')
            ->type('IDontKnow', 'password')
            ->press('submit')
            ->seePageIs('/login');
    }
}
