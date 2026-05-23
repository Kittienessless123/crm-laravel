<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('product_id')->nullable()->comment('Внешний ID из прайса');
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->foreignUuid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignUuid('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('size')->nullable();

            // Цены (базовые, могут отличаться у поставщиков)
            $table->decimal('base_price', 15, 2)->nullable();
            $table->decimal('retail_price', 15, 2)->nullable();
            $table->decimal('wholesale_price', 15, 2)->nullable();
            $table->string('currency')->default('RUB');
            $table->float('margin')->nullable()->comment('Наценка %');

            // Связи
            $table->foreignUuid('seller_id')->nullable()->constrained('sellers')->nullOnDelete();
            $table->foreignUuid('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();

            // Статусы
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);

            // Медиа
            $table->string('photo_url')->nullable();

            // Дополнительно
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Индексы
            $table->index('product_name');
            $table->index('sku');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
