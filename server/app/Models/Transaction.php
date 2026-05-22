<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

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

    public static function generateTransactionNumber(): string
    {
        return 'TXN-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function scopeWithDefaults(Builder $query): Builder
    {
        return $query->with(['seller', 'items', 'payments']);
    }
    public function scopeWithRelations(Builder $query, array|string $relations): Builder
    {
        if (is_string($relations)) {
            return $query->with(explode(',', $relations));
        }

        return $query->with($relations);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            // Поиск по номеру транзакции, описанию, инвойсу
            ->when($filters['search'] ?? null, function (Builder $q, string $search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('transaction_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('invoice_number', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            // Основные фильтры
            ->when($filters['seller_id'] ?? null, fn(Builder $q, string $v) => $q->where('seller_id', $v))
            ->when($filters['transaction_number'] ?? null, fn(Builder $q, string $v) => $q->where('transaction_number', $v))
            ->when($filters['sku'] ?? null, fn(Builder $q, string $v) => $q->where('sku', $v))
            ->when($filters['currency'] ?? null, fn(Builder $q, string $v) => $q->where('currency', strtoupper($v)))
            ->when($filters['payment_type'] ?? null, fn(Builder $q, string $v) => $q->where('payment_type', $v))
            ->when($filters['status'] ?? null, fn(Builder $q, string $v) => $q->where('status', $v))
            ->when($filters['payment_gateway'] ?? null, fn(Builder $q, string $v) => $q->where('payment_gateway', $v))
            ->when($filters['payment_gateway_id'] ?? null, fn(Builder $q, string $v) => $q->where('payment_gateway_id', $v))
            ->when($filters['discount_type'] ?? null, fn(Builder $q, string $v) => $q->where('discount_type', $v))
            ->when($filters['promo_code'] ?? null, fn(Builder $q, string $v) => $q->where('promo_code', $v))
            ->when($filters['invoice_number'] ?? null, fn(Builder $q, string $v) => $q->where('invoice_number', $v))

            // Булевы
            ->when(isset($filters['is_prepayment']), fn(Builder $q) => $q->where('is_prepayment', $filters['is_prepayment']))

            // Суммы
            ->when($filters['min_amount'] ?? null, fn(Builder $q, float $v) => $q->where('amount', '>=', $v))
            ->when($filters['max_amount'] ?? null, fn(Builder $q, float $v) => $q->where('amount', '<=', $v))
            ->when($filters['min_prepayment_amount'] ?? null, fn(Builder $q, float $v) => $q->where('prepayment_amount', '>=', $v))
            ->when($filters['max_prepayment_amount'] ?? null, fn(Builder $q, float $v) => $q->where('prepayment_amount', '<=', $v))
            ->when($filters['min_discount_amount'] ?? null, fn(Builder $q, float $v) => $q->where('discount_amount', '>=', $v))
            ->when($filters['max_discount_amount'] ?? null, fn(Builder $q, float $v) => $q->where('discount_amount', '<=', $v))

            // Даты: transaction_date
            ->when($filters['transaction_date'] ?? null, fn(Builder $q, string $v) => $q->whereDate('transaction_date', $v))
            ->when($filters['transaction_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('transaction_date', '>=', $v))
            ->when($filters['transaction_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('transaction_date', '<=', $v))

            // Даты: payment_received_date
            ->when($filters['payment_received_date'] ?? null, fn(Builder $q, string $v) => $q->whereDate('payment_received_date', $v))
            ->when($filters['payment_received_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('payment_received_date', '>=', $v))
            ->when($filters['payment_received_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('payment_received_date', '<=', $v))

            // Даты: creation_date (из трейта)
            ->when($filters['creation_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('creation_date', '>=', $v))
            ->when($filters['creation_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('creation_date', '<=', $v))
            ->when($filters['updated_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('updated_date', '>=', $v))
            ->when($filters['updated_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('updated_date', '<=', $v));
    }

    public function scopeApplySorting(Builder $query, string $sortBy = 'creation_date', string $direction = 'desc'): Builder
    {
        $allowedSorts = [
            'transaction_number',
            'amount',
            'currency',
            'payment_type',
            'status',
            'transaction_date',
            'payment_received_date',
            'prepayment_amount',
            'discount_amount',
            'creation_date',
            'updated_date',
        ];

        if (in_array($sortBy, $allowedSorts)) {
            return $query->orderBy($sortBy, $direction === 'asc' ? 'asc' : 'desc');
        }

        return $query->latest('creation_date');
    }
}
