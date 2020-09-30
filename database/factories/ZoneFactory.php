<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Zone;
use Faker\Generator as Faker;

/*
 * Zone Model Factory
 */

$factory->define(Zone::class, function (Faker $faker) {
    return [
        'domain'            => $faker->unique()->domainName . '.',
        'serial'            => Zone::generateSerialNumber(),
        'master_server'     => $faker->optional()->ipv4,
        'has_modifications' => true,
        'reverse_zone'      => false,
        'custom_settings'   => false,
        'refresh'           => null,
        'retry'             => null,
        'expire'            => null,
        'negative_ttl'      => null,
        'default_ttl'       => null,
    ];
});

$factory->defineAs(Zone::class, 'reverse', function (Faker $faker) {
    $parts = explode('.', $faker->unique()->ipv4, -1);
    $reverse_ip = implode('.', array_reverse($parts));
    $reverseZoneName = $reverse_ip . '.in-addr.arpa.';

    return [
        'domain'            => $reverseZoneName,
        'serial'            => Zone::generateSerialNumber(),
        'master_server'     => $faker->optional()->ipv4,
        'has_modifications' => true,
        'reverse_zone'      => true,
        'custom_settings'   => false,
        'refresh'           => null,
        'retry'             => null,
        'expire'            => null,
        'negative_ttl'      => null,
        'default_ttl'       => null,
    ];
});
