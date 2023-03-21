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

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        setting()->set([
            /*
             * Default values for Zone definition.
             */
            'zone_default_mname' => 'dns1.domain.local', // Default MNAME, hostname of master DNS
            'zone_default_rname' => 'hostmaster@domain.local', // Default RNAME, email of hostmaster
            'zone_default_refresh' => '86400', // 24 hours
            'zone_default_retry' => '7200', // 2 hours
            'zone_default_expire' => '3628800', // 6 weeks
            'zone_default_negative_ttl' => '7200', // 2 hours
            'zone_default_default_ttl' => '172800', // 48 hours

            /*
             * This is the username that ProBIND will use to connect remote hosts.
             */
            'ssh_default_user' => 'probinder',

            /*
             * ProBIND Host Private Key, used to access to DNS hosts.
             */
            'ssh_default_key' => '-----BEGIN OPENSSH PRIVATE KEY-----
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
ePBOdQDy/CSPJNAAAACnBhY29AY2lncm8=
-----END OPENSSH PRIVATE KEY-----',

            /*
            * Port to use as SSH
            */
            'ssh_default_port' => '2222',

            /*
             * This is the folder whre ProBIND will generate files on remote hosts.
             */
            'ssh_default_remote_path' => '/home/probinder/data',
        ]);
    }
}
