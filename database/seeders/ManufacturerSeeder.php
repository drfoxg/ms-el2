<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $model = new Models\Manufacturer();
        $table = $model->getTable();
        $tablePrefix = $model->getConnection()->getTablePrefix();

        \DB::statement("LOCK TABLES {$tablePrefix}{$table} WRITE;");
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        \DB::statement("TRUNCATE {$tablePrefix}{$table};");
        \DB::statement("INSERT INTO {$tablePrefix}{$table} VALUES (1, 'Littelfuse Inc', 'Littelfuse', 'Littelfuse', '2023-09-28 17:40:47', '2023-10-23 17:40:47'), (2, 'Coilcraft', 'Coilcraft', 'Coilcraft', '2023-10-23 17:40:47', '2023-09-28 17:40:47');");
        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        \DB::statement("UNLOCK TABLES;");
    }
}
