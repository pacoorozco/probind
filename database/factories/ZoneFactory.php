<?php

namespace Database\Factories;

use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'domain' => $this->faker->unique()->domainName().'.',
            'serial' => Zone::calculateNewSerialNumber(),
            'server' => $this->faker->optional()->ipv4(),
            'has_modifications' => true,
            'reverse_zone' => false,
            'custom_settings' => false,
            'refresh' => null,
            'retry' => null,
            'expire' => null,
            'negative_ttl' => null,
            'default_ttl' => null,
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
                'server' =>  $this->faker->ipv4(),
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
