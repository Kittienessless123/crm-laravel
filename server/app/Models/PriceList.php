<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceList extends Model
{
    use HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'supplier_id',
        'product_id',
        'retail_price',
        'wholesale_price',
        'currency',
        'availability_date',
        'is_active',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'retail_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'uploaded_at' => 'datetime',
            'availability_date' => 'string',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


      public function scopeFilter($query, array $filters): void
    {
        $query
            ->when($filters['supplier_id'] ?? null, fn($q, $v) => $q->where('supplier_id', $v))
            ->when($filters['product_id'] ?? null, fn($q, $v) => $q->where('product_id', $v))
            ->when($filters['min_price'] ?? null, fn($q, $v) => $q->where('retail_price', '>=', $v))
            ->when($filters['max_price'] ?? null, fn($q, $v) => $q->where('retail_price', '<=', $v))
            ->when($filters['min_wholesale_price'] ?? null, fn($q, $v) => $q->where('wholesale_price', '>=', $v))
            ->when($filters['max_wholesale_price'] ?? null, fn($q, $v) => $q->where('wholesale_price', '<=', $v))
            ->when($filters['currency'] ?? null, fn($q, $v) => $q->where('currency', $v))
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->when($filters['availability_date'] ?? null, fn($q, $v) => $q->where('availability_date', $v))
            ->when($filters['uploaded_at_from'] ?? null, fn($q, $v) => $q->where('uploaded_at', '>=', $v))
            ->when($filters['uploaded_at_to'] ?? null, fn($q, $v) => $q->where('uploaded_at', '<=', $v));
    }
/* 
вызов в контроллере : 

      public function index(Request $request)
    {
        // 🎯 Вот так передаешь фильтры!
        $filters = [
            'supplier_id' => $request->input('supplier_id'),        // uuid поставщика
            'product_id' => $request->input('product_id'),          // uuid товара
            'min_price' => $request->input('min_price'),            // минимальная розничная цена
            'max_price' => $request->input('max_price'),            // максимальная розничная цена
            'currency' => $request->input('currency'),              // валюта (USD, RUB, EUR)
            'is_active' => $request->boolean('is_active'),          // активный?
            'availability_date' => $request->input('availability_date'), // дата доступности
        ];

        $priceList = $this->priceListRepo->getPriceListFilteredPaginated($filters);

        return response()->json($priceList);
    } */
}