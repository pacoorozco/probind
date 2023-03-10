<?php

namespace Database\Factories;

use App\Enums\ResourceRecordType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->domainWord(),
            'type' => ResourceRecordType::A,
            'data' => fake()->ipv4(),
            'ttl' => null,
        ];
    }

    /**
     * Indicate that the model's type is an A record.
     */
    public function asARecord(): static
    {
        return $this->state(function (array $attributes) {
            return $attributes;
        });
    }

    /**
     * Indicate that the model's type is a CNAME record.
     */
    public function asCNAMERecord(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::CNAME,
                'data' => fake()->domainWord().'.'.fake()->domainName().'.',
            ];
        });
    }

    /**
     * Indicate that the model's type is a MX record.
     */
    public function asMXRecord(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::MX,
                'priority' => fake()->randomElement([10, 20, 30]),
                'data' => sprintf('%d %s',
                    fake()->randomElement([10, 20, 30]),
                    fake()->domainWord().'.'.fake()->domainName().'.'),
            ];
        });
    }

    /**
     * Indicate that the model's type is a NS record.
     */
    public function asNSRecord(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::NS,
            ];
        });
    }

    /**
     * Indicate that the model's type is a TXT record.
     */
    public function asTXTRecord(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::TXT,
                'data' => fake()->text,
            ];
        });
    }

    /**
     * Indicate that the model's type is a PTR record.
     */
    public function asPTRRecord(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => fake()->numberBetween(1, 254),
                'type' => ResourceRecordType::PTR,
                'data' => fake()->domainWord().'.'.fake()->domainName().'.',
            ];
        });
    }
}
