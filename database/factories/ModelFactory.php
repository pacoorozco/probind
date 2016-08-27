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

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function(Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Server::class, function(Faker\Generator $faker) {
    return [
        'hostname'   => $faker->unique()->domainWord . '.local',
        'ip_address' => $faker->ipv4,
        'type'       => $faker->randomElement(['master', 'slave']),
        'directory'  => '/tmp/' . $faker->word,
        'script'     => $faker->word,
    ];
});

$factory->define(App\Zone::class, function(Faker\Generator $faker) {
    return [
        'domain' => $faker->unique()->domainName,
        'master' => $faker->optional()->ipv4,
    ];
});

$factory->defineAs(App\Record::class, 'A', function(Faker\Generator $faker) {
    // Return an A record
    return [
        'name' => $faker->domainWord,
        'type' => 'A',
        'data' => $faker->ipv4,
    ];
});

$factory->defineAs(App\Record::class, 'CNAME', function(Faker\Generator $faker) {
    // Return a CNAME record
    return [
        'name' => $faker->domainWord,
        'type' => 'CNAME',
        'data' => $faker->domainWord . '.' . $faker->domainName . '.',
    ];
});

