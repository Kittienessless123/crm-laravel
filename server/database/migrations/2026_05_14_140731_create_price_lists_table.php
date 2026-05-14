<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Связи
            $table->foreignUuid('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            
            // Цены
            $table->decimal('retail_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2)->nullable();
            $table->string('currency')->default('RUB');
            
            // Готовность
            $table->string('availability_date')->nullable()->comment('май-июнь, Сентябрь');
            
            // Статус
            $table->boolean('is_active')->default(true);
            $table->datetime('uploaded_at')->nullable();
            
            $table->timestamps();
            
            // Индексы
            $table->index('is_active');
            $table->unique(['supplier_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};