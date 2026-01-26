<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $model = new Models\Category();
        $table = $model->getTable();
        $tablePrefix = $model->getConnection()->getTablePrefix();

        \DB::statement("LOCK TABLES {$tablePrefix}{$table} WRITE;");
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        \DB::statement("TRUNCATE {$tablePrefix}{$table};");

        \DB::statement("
                INSERT INTO {$tablePrefix}{$table}
                (id, name, slug, parent_id, created_at, updated_at, deleted_at)
                VALUES
                (1, 'Без категории', 'no-category', NULL, NOW(), NOW(), NULL),
                (2, 'Резисторы', 'resistors', NULL, NOW(), NOW(), NULL),
                (3, 'Конденсаторы', 'capacitors', NULL, NOW(), NOW(), NULL),
                (4, 'Чип-резисторы', 'chip-resistors', 2, NOW(), NOW(), NULL)
            ");

        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        \DB::statement("UNLOCK TABLES;");
    }
}
