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
        \DB::statement("INSERT INTO {$tablePrefix}{$table} VALUES (1, '0217.250MXP', 1, 1, 20, 'Просто коммент', '2023-09-28 17:40:47', '2023-10-23 17:40:47'), (2, '0217001.MXP', 1, 1, 30, 'Просто коммент 1', '2023-10-23 17:40:47', '2023-09-28 17:40:47'), (3, '0603CS-36NXJLW', 2, 2, 1330, 'Просто коммент 2', '2023-10-23 17:40:47', '2023-09-28 17:40:47');");
        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        \DB::statement("UNLOCK TABLES;");
    }
}
