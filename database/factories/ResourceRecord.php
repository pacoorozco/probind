<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Record;
use Faker\Generator as Faker;

/*
 * Record Model Factories
 */

$factory->defineAs(Record::class, 'A', function (Faker $faker) {
    // Return an A record
    return [
        'name' => $faker->domainWord,
        'ttl' => null,
        'type' => 'A',
        'priority' => null,
        'data' => $faker->ipv4,
    ];
});

$factory->defineAs(Record::class, 'CNAME', function (Faker $faker) {
    // Return a CNAME record
    return [
        'name' => $faker->domainWord,
        'ttl' => null,
        'type' => 'CNAME',
        'priority' => null,
        'data' => $faker->domainWord . '.' . $faker->domainName . '.',
    ];
});

$factory->defineAs(Record::class, 'MX', function (Faker $faker) {
    // Return a MX record
    return [
        'name' => $faker->domainWord,
        'ttl' => null,
        'type' => 'MX',
        'priority' => $faker->randomElement([10, 20, 30]),
        'data' => $faker->domainWord . '.' . $faker->domainName . '.',
    ];
});
