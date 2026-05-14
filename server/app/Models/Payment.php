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
}