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

use App\Models\Server;
use Tests\TestCase;

class ServerTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider providesHostnames
     */
    public function hostname_is_lowercase(string $testHostname, string $want): void
    {
        /** @var Server $server */
        $server = Server::factory()->make([
            'hostname' => $testHostname,
        ]);

        $this->assertEquals($want, $server->hostname);
    }

    public static function providesHostnames(): array
    {
        return [
            'lowercase hostname' => ['server01.local', 'server01.local'],
            'uppercase hostname' => ['SERVER01.LOCAL', 'server01.local'],
            'mixed-case hostname' => ['dns1.DOMAIN.com', 'dns1.domain.com'],
        ];
    }
}
