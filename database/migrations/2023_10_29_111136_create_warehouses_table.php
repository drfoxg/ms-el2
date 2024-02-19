<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->index()->notnull()->comment('Название модели компонента, которое дал производитель');;

            //$table->string('manufacturer', 70)->index()->notnull()->comment('Производитель номенклатуры');
            //$table->string('vendor', 50)->index()->notnull()->comment('Поставщик');

            $table->foreignIdFor(Models\Manufacturer::class)->index()->nullable()->constrained()->restrictOnDelete()->comment('Внешний ключ на Производитель номенклатуры');
            $table->foreignIdFor(Models\Vendor::class)->index()->nullable()->constrained()->restrictOnDelete()->comment('Внешний ключ на Поставщик');

            $table->unsignedInteger('stock_quantity')->notnull()->default(0)->comment('Количество');
            $table->string('comment', 300)->index()->notnull()->comment('Комментарий');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

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
