<?php

use Illuminate\Database\Seeder;
use Torann\Registry\Facades\Registry;

class SettingsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Registry::flush();

        $settings = [
            /*
             * Default values for Zone's definition
             */
            'zone_default' => [
                'mname'        => 'dns1.domain.local', // Default MNAME, hostname of master DNS
                'rname'        => 'hostmaster@domain.local', // Default RNAME, email of hostmaster
                'refresh'      => '86400', // 24 hours
                'retry'        => '7200', // 2 hours
                'expire'       => '3628800', // 6 weeks
                'negative_ttl' => '7200', // 2 hours
                'default_ttl'  => '172800' // 48 hours
            ],
        ];

        Registry::store($settings);
    }
}
