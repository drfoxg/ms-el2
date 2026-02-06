<?php

namespace App\Console\Commands;

use App\Models\Manufacturer;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateWarehouses extends Command
{
    protected $signature = 'warehouses:generate {count=500000}';
    protected $description = 'Генерация товаров';

    public function handle(): void
    {
        $count = (int) $this->argument('count');
        $chunkSize = 5000;

        // Предварительно загружаем ID связанных сущностей
        $categoryIds = Category::pluck('id')->toArray();
        $manufacturerIds = Manufacturer::pluck('id')->toArray();
        $vendorIds = Vendor::pluck('id')->toArray();

        if (empty($categoryIds) || empty($manufacturerIds) || empty($vendorIds)) {
            $this->error('Сначала создай категории, производителей и поставщиков');
            return;
        }

        $this->info("Генерация {$count} товаров...");
        $bar = $this->output->createProgressBar($count);

        // Отключаем логирование запросов
        DB::disableQueryLog();

        for ($i = 0; $i < $count; $i += $chunkSize) {

            $this->newLine();
            $this->info("Прогресс: {$i}, Память: " . $this->getMemoryUsage());

            $records = [];
            $batchSize = min($chunkSize, $count - $i);

            for ($j = 0; $j < $batchSize; $j++) {
                $records[] = [
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'part_number' => fake()->unique()->bothify('??##-####-??'),
                    'name' => fake()->words(3, true),
                    'price' => fake()->randomFloat(2, 10, 10000),
                    'in_stock' => fake()->boolean(70),
                    'rating' => fake()->randomFloat(1, 0, 5),
                    'manufacturer_id' => $manufacturerIds[array_rand($manufacturerIds)],
                    'vendor_id' => $vendorIds[array_rand($vendorIds)],
                    'stock_quantity' => fake()->numberBetween(0, 1000),
                    'comment' => fake()->optional(0.3)->sentence(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('warehouses')->insert($records);
            $bar->advance($batchSize);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Готово!');
    }

    private function getMemoryUsage(): string
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB';
    }
}
