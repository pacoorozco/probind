<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'domain' => $this->faker->unique()->domainName().'.',
            'serial' => '2020010100',
            'server' => $this->faker->optional()->ipv4(),
            'has_modifications' => $this->faker->boolean,
            'reverse_zone' => false,
            'custom_settings' => $this->faker->boolean,
            'refresh' => $this->faker->numberBetween(3600, 86400),
            'retry' => $this->faker->numberBetween(3600, 86400),
            'expire' => $this->faker->numberBetween(3600, 86400),
            'negative_ttl' => $this->faker->numberBetween(3600, 86400),
            'default_ttl' => $this->faker->numberBetween(3600, 86400),
        ];
    }

    public function primary(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'server' => null,
            ];
        });
    }

    public function secondary(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'server' => $this->faker->ipv4(),
            ];
        });
    }

    public function reverse(): Factory
    {
        return $this->state(function (array $attributes) {
            $ipAddressParts = explode('.', $this->faker->unique()->ipv4());

            return [
                'domain' => "{$ipAddressParts[2]}.{$ipAddressParts[1]}.{$ipAddressParts[0]}.in-addr.arpa.",
                'reverse_zone' => true,
            ];
        });
    }
}
