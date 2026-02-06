<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

// Один поставщик
// $vendor = Vendor::factory()->create();

// 5 поставщиков
// Vendor::factory()->count(5)->create();

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
        ];
    }
}
