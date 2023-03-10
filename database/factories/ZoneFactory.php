<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'domain' => fake()->unique()->domainName().'.',
            'serial' => '2020010100',
            'server' => fake()->optional()->ipv4(),
            'has_modifications' => fake()->boolean,
            'reverse_zone' => false,
            'custom_settings' => fake()->boolean,
            'refresh' => fake()->numberBetween(3600, 86400),
            'retry' => fake()->numberBetween(3600, 86400),
            'expire' => fake()->numberBetween(3600, 86400),
            'negative_ttl' => fake()->numberBetween(3600, 86400),
            'default_ttl' => fake()->numberBetween(3600, 86400),
        ];
    }

    /**
     * Indicate that the model's type is a Primary Zone.
     */
    public function primary(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'server' => null,
            ];
        });
    }

    /**
     * Indicate that the model's type is a Secondary Zone.
     */
    public function secondary(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'server' => fake()->ipv4(),
            ];
        });
    }

    /**
     * Indicate that the model's type is a Reverse Zone.
     */
    public function reverse(): static
    {
        return $this->state(function (array $attributes) {
            $ipAddressParts = explode('.', fake()->unique()->ipv4());

            return [
                'domain' => "{$ipAddressParts[2]}.{$ipAddressParts[1]}.{$ipAddressParts[0]}.in-addr.arpa.",
                'reverse_zone' => true,
            ];
        });
    }
}
