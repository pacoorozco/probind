<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace Tests\Feature;

use Tests\TestCase;

class InstallHttpTest extends TestCase
{
    /**
     * Test install URI with a previous installation.
     */
    public function testInstallURIFailure()
    {
        \Storage::disk('local')->put('installed', '');

        $this->get('/install')
            ->assertStatus(404);
    }

    /**
     * Test install URI without a previous installation.
     */
    public function testInstallURISuccess()
    {
        \Storage::disk('local')->delete('installed');

        $this->get('/install')
            ->assertStatus(200);
    }
}
