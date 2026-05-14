<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Связи
            $table->foreignUuid('transaction_id')->constrained('transactions')->cascadeOnDelete();
            
            // Платёж
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->comment('card, bank_transfer, cash, sbp');
            $table->datetime('payment_date');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_gateway_id')->nullable();
            $table->string('status')->default('pending')->comment('pending, completed, failed, refunded');
            
            // Дополнительно
            $table->json('metadata')->nullable()->comment('Ответ от платёжного шлюза');
            $table->timestamps();
            
            // Индексы
            $table->index('status');
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};