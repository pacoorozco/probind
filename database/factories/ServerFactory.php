<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory {

    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'hostname' => $this->faker->unique()->domainWord . '.local',
            'ip_address' => $this->faker->ipv4,
            'type' => $this->faker->randomElement(['master', 'slave']),
            'push_updates' => false,
            'ns_record' => false,
            'active' => true,
        ];
    }
}

