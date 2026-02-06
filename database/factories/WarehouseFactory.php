<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

// Пример использования
// Один товар
// $warehouse = Warehouse::factory()->create();

// 10 товаров в наличии
// $warehouses = Warehouse::factory()->count(10)->inStock()->create();

// Товар без связей (использует дефолтную категорию)
// $warehouse = Warehouse::factory()->withoutRelations()->create();

// Комбинация состояний
// $warehouse = Warehouse::factory()
//     ->inStock()
//     ->highRated()
//     ->create(['name' => 'Конкретное имя']);


/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'part_number' => strtoupper($this->faker->bothify('??-####-???')),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 10000),
            'in_stock' => $this->faker->boolean(70),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'manufacturer_id' => Manufacturer::factory(),
            'vendor_id' => Vendor::factory(),
            'stock_quantity' => $this->faker->numberBetween(0, 500),
            'comment' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    /**
     * Товар в наличии
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'in_stock' => true,
            'stock_quantity' => $this->faker->numberBetween(1, 500),
        ]);
    }

    /**
     * Товар отсутствует
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'in_stock' => false,
            'stock_quantity' => 0,
        ]);
    }

    /**
     * Без производителя и поставщика
     */
    public function withoutRelations(): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => 1,
            'manufacturer_id' => null,
            'vendor_id' => null,
        ]);
    }

    /**
     * С высоким рейтингом (4-5)
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 4, 5),
        ]);
    }
}
