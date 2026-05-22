<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'transaction_id',
        'amount',
        'payment_method',        // card, bank_transfer, cash
        'payment_date',
        'payment_gateway',       // stripe, yookassa
        'payment_gateway_id',    // ID в платёжной системе
        'status',                // pending, completed, failed, refunded
        'metadata',              // JSON с ответом от платёжного шлюза
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount' => 'decimal:2',
            'metadata' => 'json',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }


    public function scopeFilter($query, array $filters): void
    {

        $query
            // ID и связи
            ->when($filters['id'] ?? null, fn($q, $v) => $q->where('id', $v))
            ->when($filters['transaction_id'] ?? null, fn($q, $v) => $q->where('transaction_id', $v))

            // Сумма (диапазон)
            ->when($filters['amount'] ?? null, fn($q, $v) => $q->where('amount', $v))
            ->when($filters['amount_min'] ?? null, fn($q, $v) => $q->where('amount', '>=', $v))
            ->when($filters['amount_max'] ?? null, fn($q, $v) => $q->where('amount', '<=', $v))

            // Способ оплаты (может быть массивом для IN)
            ->when($filters['payment_method'] ?? null, function ($q, $v) {
                is_array($v)
                    ? $q->whereIn('payment_method', $v)
                    : $q->where('payment_method', $v);
            })

            // Платёжный шлюз
            ->when($filters['payment_gateway'] ?? null, function ($q, $v) {
                is_array($v)
                    ? $q->whereIn('payment_gateway', $v)
                    : $q->where('payment_gateway', $v);
            })
            ->when($filters['payment_gateway_id'] ?? null, fn($q, $v) => $q->where('payment_gateway_id', $v))

            // Статус (может быть массивом для IN)
            ->when($filters['status'] ?? null, function ($q, $v) {
                is_array($v)
                    ? $q->whereIn('status', $v)
                    : $q->where('status', $v);
            })

            // Дата платежа (диапазон)
            ->when($filters['payment_date'] ?? null, fn($q, $v) => $q->whereDate('payment_date', $v))
            ->when($filters['payment_date_from'] ?? null, fn($q, $v) => $q->where('payment_date', '>=', $v))
            ->when($filters['payment_date_to'] ?? null, fn($q, $v) => $q->where('payment_date', '<=', $v))

            // Поиск по metadata (JSON-поле)
            ->when($filters['metadata_search'] ?? null, function ($q, $v) {
                foreach ($v as $key => $value) {
                    $q->whereJsonContains('metadata->' . $key, $value);
                }
            });
    }
}
