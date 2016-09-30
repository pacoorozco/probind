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

use Illuminate\Foundation\Testing\DatabaseMigrations;

class SettingsHttpTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();
        $this->actingAs($user);
    }

    /**
     * Test update settings form.
     */
    public function testUpdateSettings()
    {
        // Table seeder
        $this->seed(TestingDatabaseSeeder::class);

        $expectedSettings = [
            'zone_default_mname'        => 'dns.domain.com',
            'zone_default_rname'        => 'my_mail@domain.com',
            'zone_default_refresh'      => '1001',
            'zone_default_retry'        => '1002',
            'zone_default_expire'       => '1003',
            'zone_default_negative_ttl' => '1004',
            'zone_default_default_ttl'  => '1005',

            'ssh_default_user'        => 'user',
            'ssh_default_key'         => 'My SSH Public Key',
            'ssh_default_port'        => '2022',
            'ssh_default_remote_path' => '/etc/named/user',
        ];

        // Modify settings with expected values.
        $this->visit('settings')
            ->type($expectedSettings['zone_default_mname'], 'zone_default_mname')
            ->type($expectedSettings['zone_default_rname'], 'zone_default_rname')
            ->type($expectedSettings['zone_default_refresh'], 'zone_default_refresh')
            ->type($expectedSettings['zone_default_retry'], 'zone_default_retry')
            ->type($expectedSettings['zone_default_expire'], 'zone_default_expire')
            ->type($expectedSettings['zone_default_negative_ttl'], 'zone_default_negative_ttl')
            ->type($expectedSettings['zone_default_default_ttl'], 'zone_default_default_ttl')
            ->type($expectedSettings['ssh_default_user'], 'ssh_default_user')
            ->type($expectedSettings['ssh_default_key'], 'ssh_default_key')
            ->type($expectedSettings['ssh_default_port'], 'ssh_default_port')
            ->type($expectedSettings['ssh_default_remote_path'], 'ssh_default_remote_path')
            ->press('Save data');

        // Get new settings from database.
        $settings = array_only(Setting::all()->toArray(), array_keys($expectedSettings));

        $this->assertEquals($expectedSettings, $settings);
    }
}
