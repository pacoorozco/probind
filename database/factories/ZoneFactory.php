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
            'serial' => Zone::generateSerialNumber(),
            'master_server' => $this->faker->optional()->ipv4(),
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

    public function reverse(): Factory
    {
        $parts = explode('.', $this->faker->ipv4(), -1);
        $reverse_ip = implode('.', array_reverse($parts));
        $reverseZoneName = $reverse_ip.'.in-addr.arpa.';

        return $this->state(function (array $attributes) use ($reverseZoneName) {
            return [
                'domain' => $reverseZoneName,
                'reverse_zone' => true,
            ];
        });
    }
}
