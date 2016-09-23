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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2016 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/probind
 *
 */

use Illuminate\Database\Seeder;


class SettingsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            /*
             * Default values for Zone's definition
             */
            'zone_default_mname'        => 'dns1.domain.local', // Default MNAME, hostname of master DNS
            'zone_default_rname'        => 'hostmaster@domain.local', // Default RNAME, email of hostmaster
            'zone_default_refresh'      => '86400', // 24 hours
            'zone_default_retry'        => '7200', // 2 hours
            'zone_default_expire'       => '3628800', // 6 weeks
            'zone_default_negative_ttl' => '7200', // 2 hours
            'zone_default_default_ttl'  => '172800', // 48 hours

            'ssh_default_user'        => 'probinder',
            'ssh_default_key'         => 'Please add a SSH private key here!',
            'ssh_default_port'        => '22',
            'ssh_default_remote_path' => '/etc/named/probinder',
        ];

        Setting::set($settings);
    }
}
