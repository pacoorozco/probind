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

namespace Tests\Unit\Models;

use App\Enums\ServerType;
use App\Models\Server;
use Tests\TestCase;

class ServerTest extends TestCase
{
    /** @test */
    public function hostname_is_lowercase()
    {
        $want = 'server01.local';
        $server = new Server([
                'hostname' => 'SERVER01.local',
                'ip_address' => '192.168.1.2',
                'type' => ServerType::Primary,
            ]
        );

        $this->assertEquals($want, $server->hostname);
    }

}
