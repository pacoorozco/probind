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

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTestCase;

class DashboardHttpTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Test Dashboard creation.
     */
    public function testVisitDashboard()
    {
        $this->visit('/')
            ->seeElement('#info-boxes')
            ->seeElement('#latest-activity-widget')
            ->seeElement('#latest-jobs-widget');
    }
}
