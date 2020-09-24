<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Server;
use Faker\Generator as Faker;

/*
 * Server Model Factory
 */

$factory->define(Server::class, function (Faker $faker) {
    return [
        'hostname' => $faker->unique()->domainWord . '.local',
        'ip_address' => $faker->ipv4,
        'type' => $faker->randomElement(['master', 'slave']),
        'push_updates' => false,
        'ns_record' => false,
        'active' => true,
    ];
});
