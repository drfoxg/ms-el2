<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Models\Category::class)
                ->default(1) // дефолтная категория "Без категории"
                ->constrained()
                ->restrictOnDelete()
                ->comment('Категория товара');

            $table->string('part_number')->index()->notnull()->comment('Название модели компонента, которое дал производитель');
            $table->string('name')->notnull()->comment('Имя компонента, по которому часто ищут');

            $table->decimal('price', 12, 2)
                ->default(0)
                ->comment('Цена товара');

            $table->boolean('in_stock')
                ->default(false)
                ->comment('Есть ли товар в наличии');

            $table->float('rating')
                ->default(0)
                ->comment('Рейтинг товара (0–5)');

            $table->foreignIdFor(Models\Manufacturer::class)
                ->nullable()
                ->constrained()
                ->restrictOnDelete()
                ->comment('Внешний ключ на Производитель номенклатуры');

            $table->foreignIdFor(Models\Vendor::class)
                ->nullable()
                ->constrained()
                ->restrictOnDelete()
                ->comment('Внешний ключ на Поставщик');

            $table->unsignedInteger('stock_quantity')->notnull()->default(0)->comment('Количество');
            $table->string('comment', 300)->nullable()->comment('Комментарий');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->fullText(['name', 'comment']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
