<?php

namespace Database\Factories;

use App\Enums\ServerType;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{
    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'hostname' => $this->faker->unique()->domainWord() . '.local',
            'ip_address' => $this->faker->ipv4(),
            'type' => $this->faker->randomElement(ServerType::getValues()),
            'push_updates' => $this->faker->boolean(),
            'ns_record' => $this->faker->boolean(),
            'active' => $this->faker->boolean(),
        ];
    }
}
