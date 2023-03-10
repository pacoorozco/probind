<?php

namespace Database\Factories;

use App\Enums\ServerType;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hostname' => fake()->unique()->domainWord() . '.local',
            'ip_address' => fake()->ipv4(),
            'type' => fake()->randomElement(ServerType::getValues()),
            'push_updates' => fake()->boolean(),
            'ns_record' => fake()->boolean(),
            'active' => fake()->boolean(),
        ];
    }
}
