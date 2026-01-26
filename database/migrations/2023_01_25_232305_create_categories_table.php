<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)
                  ->index()
                  ->comment('Название категории товаров');
            $table->string('slug', 120)
                  ->unique()
                  ->comment('URL-friendly идентификатор категории');
            $table->string('description', 255)
                  ->nullable()
                  ->comment('Описание категории');
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->cascadeOnDelete()
                  ->comment('Родительская категория');
            $table->timestamps();
            $table->softDeletes();
        });

        // Категория "Без категории"
        \DB::table('categories')->insert([
            'id' => 1,
            'name' => 'Без категории',
            'slug' => 'no-category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
