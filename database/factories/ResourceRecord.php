<?php

namespace Database\Factories;

use App\Models\ResourceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceRecordFactory extends Factory
{

    protected $model = ResourceRecord::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->domainWord,
            'ttl' => null,
            'type' => 'A',
            'priority' => null,
            'data' => $this->faker->ipv4,
        ];
    }

    public function asARecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return $attributes;
        });
    }

    public function asCNAMERecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'CNAME',
                'data' => $this->faker->domainWord.'.'.$this->faker->domainName.'.',
            ];
        });
    }

    public function asMXRecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'MX',
                'priority' => $this->faker->randomElement([10, 20, 30]),
                'data' => $this->faker->domainWord.'.'.$this->faker->domainName.'.',
            ];
        });
    }
}

