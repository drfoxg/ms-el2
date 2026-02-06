<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

// Один производитель
// $manufacturer = Manufacturer::factory()->create();

// 10 производителей
// Manufacturer::factory()->count(10)->create();

// Производитель без публичного бренда
// Manufacturer::factory()->withoutPublicBrand()->create();

class ManufacturerFactory extends Factory
{
    protected $model = Manufacturer::class;

    public function definition(): array
    {
        $company = fake()->unique()->company();

        return [
            'name' => $company,
            'brand' => fake()->word(),
            'public_brand' => fake()->optional(0.7)->word(),
        ];
    }

    /**
     * Без публичного бренда
     */
    public function withoutPublicBrand(): static
    {
        return $this->state(fn (array $attributes) => [
            'public_brand' => null,
        ]);
    }
}
