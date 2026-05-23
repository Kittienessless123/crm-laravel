<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Связи
            $table->foreignUuid('seller_id')->constrained('sellers')->cascadeOnDelete();

            // Номер и статус
            $table->string('transaction_number')->unique()->comment('TXN-20260514-ABC123');
            $table->string('status')->default('draft')->comment('draft, pending, paid, partially_paid, cancelled, refunded, completed');

            // Суммы
            $table->decimal('amount', 15, 2)->comment('Сумма сделки');
            $table->string('currency')->default('RUB');
            $table->decimal('prepayment_amount', 15, 2)->nullable();
            $table->boolean('is_prepayment')->default(false);

            // Платёж
            $table->string('payment_type')->comment('CASH, CARD, TRANSFER, SBP, CRYPTO');
            $table->string('payment_gateway')->nullable()->comment('stripe, yookassa, sbp');
            $table->string('payment_gateway_id')->nullable()->comment('ID в платёжной системе');

            // Даты
            $table->datetime('transaction_date');
            $table->datetime('payment_received_date')->nullable();

            // Скидки
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->string('discount_type')->nullable()->comment('percentage, fixed');
            $table->string('promo_code')->nullable();

            // Товар
            $table->string('sku')->nullable();

            // Документы
            $table->string('invoice_number')->nullable();
            $table->string('invoice_url')->nullable();
            $table->string('act_url')->nullable();

            // Дополнительно
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Индексы
            $table->index('status');
            $table->index('transaction_date');
            $table->index('payment_type');

            $table->index('transaction_number');
            $table->index('sku');
            $table->index('payment_gateway');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
