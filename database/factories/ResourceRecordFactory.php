<?php

namespace Database\Factories;

use App\Enums\ResourceRecordType;
use App\Models\ResourceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceRecordFactory extends Factory
{
    protected $model = ResourceRecord::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->domainWord(),
            'type' => ResourceRecordType::A,
            'data' => $this->faker->ipv4(),
            'ttl' => null,
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
                'type' => ResourceRecordType::CNAME,
                'data' => $this->faker->domainWord() . '.' . $this->faker->domainName() . '.',
            ];
        });
    }

    public function asMXRecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::MX,
                'priority' => $this->faker->randomElement([10, 20, 30]),
                'data' => sprintf('%d %s',
                    $this->faker->randomElement([10, 20, 30]),
                    $this->faker->domainWord() . '.' . $this->faker->domainName() . '.'),
            ];
        });
    }

    public function asNSRecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::NS,
            ];
        });
    }

    public function asTXTRecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ResourceRecordType::TXT,
                'data' => $this->faker->text,
            ];
        });
    }

    public function asPTRRecord(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->numberBetween(1, 254),
                'type' => ResourceRecordType::PTR,
                'data' => $this->faker->domainWord() . '.' . $this->faker->domainName() . '.',
            ];
        });
    }
}
