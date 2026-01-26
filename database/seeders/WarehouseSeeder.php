<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $model = new Models\Warehouse();
        $table = $model->getTable();
        $tablePrefix = $model->getConnection()->getTablePrefix();

        \DB::statement("LOCK TABLES {$tablePrefix}{$table} WRITE;");
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        \DB::statement("TRUNCATE {$tablePrefix}{$table};");

        \DB::statement("
                INSERT INTO {$tablePrefix}{$table}
                (
                    id,
                    category_id,
                    part_number,
                    name,
                    price,
                    in_stock,
                    rating,
                    manufacturer_id,
                    vendor_id,
                    stock_quantity,
                    comment,
                    created_at,
                    updated_at
                )
                VALUES

                -- Резисторы
                (1, 2, 'R-TH-10K-1%', 'Резистор 10K 1%', 2.50, 1, 4.5, 3, 1, 500, 'Резистор выводной', NOW(), NOW()),
                (2, 2, 'R-TH-1K-5%',  'Резистор 1K 5%',  1.90, 1, 4.2, 3, 2, 300, 'Резистор выводной', NOW(), NOW()),

                -- Чип-резисторы (дочерняя категория)
                (3, 4, '0603-10K-1%',   'Чип резистор 0603 10K', 0.15, 1, 4.8, 3, 1, 1200, 'SMD резистор', NOW(), NOW()),
                (4, 4, '0805-4K7-1%',   'Чип резистор 0805 4.7K', 0.18, 1, 4.6, 3, 2, 800,  'SMD резистор', NOW(), NOW()),
                (5, 4, '1206-100R-5%',  'Чип резистор 1206 100R', 0.22, 1, 4.3, 1, 1, 600,  'SMD резистор', NOW(), NOW()),

                -- Конденсаторы
                (6, 3, 'C-MLCC-100NF-50V', 'Конденсатор 100nF 50V', 0.30, 1, 4.9, 4, 1, 2000, 'Керамический MLCC', NOW(), NOW()),
                (7, 3, 'C-MLCC-10UF-16V',  'Конденсатор 10uF 16V',  0.45, 1, 4.7, 4, 2, 1500, 'Керамический MLCC', NOW(), NOW())
            ");

        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        \DB::statement("UNLOCK TABLES;");
    }
}
