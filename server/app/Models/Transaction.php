<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [
        // Основное
        'seller_id',
        'transaction_number',      // Уникальный номер транзакции (как в платёжных системах)
        'amount',                  // Сумма сделки
        'currency',                // Валюта (RUB, USD, EUR)
        'payment_type',            // Способ оплаты (CASH, CARD, TRANSFER)
        'status',                  // Статус сделки
        
        // Даты
        'transaction_date',        // Дата совершения сделки
        'payment_received_date',   // Дата поступления денег
        
        // Платёжная информация
        'is_prepayment',           // Наличие предоплаты (bool)
        'prepayment_amount',       // Сумма предоплаты
        'payment_gateway',         // Платёжный шлюз (stripe, yookassa, sbp)
        'payment_gateway_id',      // ID транзакции в платёжной системе
        
        // Скидки
        'discount_amount',         // Сумма скидки
        'discount_type',           // Тип скидки (percentage, fixed)
        'promo_code',              // Промокод
        
        // SKU и товары
        'sku',                     // SKU товара (если одна позиция)
        
        // Документы
        'invoice_number',          // Номер счета-фактуры
        'invoice_url',             // Ссылка на счет
        'act_url',                 // Ссылка на акт выполненных работ
        
        // Дополнительно
        'description',             // Описание сделки
        'metadata',                // JSON с дополнительными данными
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'payment_received_date' => 'datetime',
            'amount' => 'decimal:2',
            'prepayment_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'is_prepayment' => 'boolean',
            'metadata' => 'json',
        ];
    }

    // Связи
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Генерация уникального номера транзакции
    public static function generateTransactionNumber(): string
    {
        return 'TXN-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}