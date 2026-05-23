<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',          // Внешний ID из прайса поставщика
        'product_name',        // Название товара
        'sku',                 // Артикул/SKU
        'description',         // Описание
        'category_id',          // Связь с категорией
        'unit_id',
        'size',                // Размеры (200*100, 50мм, 20*20)

        // Цены
        'base_price',          // Базовая цена (себестоимость)
        'retail_price',        // Розничная цена
        'wholesale_price',     // Оптовая цена
        'currency',            // Валюта (RUB, USD, EUR)
        'margin',              // Наценка (%)

        // Медиа
        'photo_url',           // Ссылка на фото

        // Связи
        'seller_id',           // Продавец (из CRM)
        'supplier_id',         // Поставщик (из поиска по прайсам)

        // Статусы
        'is_active',           // Активен ли товар
        'is_available',        // В наличии

        // Дополнительно
        'metadata',            // JSON с дополнительными характеристиками
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'retail_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'margin' => 'float',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'metadata' => 'json',
        ];
    }

    // Связи
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceList::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('product_id', 'like', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, fn(Builder $q, string $v) => $q->where('category_id', $v))
            ->when($filters['supplier_id'] ?? null, fn(Builder $q, string $v) => $q->where('supplier_id', $v))
            ->when($filters['seller_id'] ?? null, fn(Builder $q, string $v) => $q->where('seller_id', $v))
            ->when($filters['sku'] ?? null, fn(Builder $q, string $v) => $q->where('sku', $v))
            ->when($filters['product_id'] ?? null, fn(Builder $q, string $v) => $q->where('product_id', $v))
            ->when($filters['currency'] ?? null, fn(Builder $q, string $v) => $q->where('currency', $v))
            ->when($filters['min_price'] ?? null, fn(Builder $q, float $v) => $q->where('retail_price', '>=', $v))
            ->when($filters['max_price'] ?? null, fn(Builder $q, float $v) => $q->where('retail_price', '<=', $v))
            ->when($filters['min_wholesale_price'] ?? null, fn(Builder $q, float $v) => $q->where('wholesale_price', '>=', $v))
            ->when($filters['max_wholesale_price'] ?? null, fn(Builder $q, float $v) => $q->where('wholesale_price', '<=', $v))
            ->when($filters['min_margin'] ?? null, fn(Builder $q, float $v) => $q->where('margin', '>=', $v))
            ->when($filters['max_margin'] ?? null, fn(Builder $q, float $v) => $q->where('margin', '<=', $v))
            ->when(isset($filters['is_active']), fn(Builder $q) => $q->where('is_active', $filters['is_active']))
            ->when(isset($filters['is_available']), fn(Builder $q) => $q->where('is_available', $filters['is_available']))
            ->when($filters['size'] ?? null, fn(Builder $q, string $v) => $q->where('size', 'like', "%{$v}%"))
            ->when($filters['availability_date'] ?? null, fn(Builder $q, string $v) => $q->where('availability_date', $v))
            ->when($filters['availability_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('availability_date', '>=', $v))
            ->when($filters['availability_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('availability_date', '<=', $v))
            ->when($filters['created_at_from'] ?? null, fn(Builder $q, string $v) => $q->where('created_at', '>=', $v))
            ->when($filters['created_at_to'] ?? null, fn(Builder $q, string $v) => $q->where('created_at', '<=', $v));
    }

    public function scopeWithRelations(Builder $query, array|string $relations): Builder
    {
        if (is_string($relations)) {
            return $query->with(explode(',', $relations));
        }

        return $query->with($relations);
    }

    public function scopeWithDefaults(Builder $query): Builder
    {
        return $query->with(['category', 'unit', 'seller', 'supplier']);
    }

    public function scopeApplySorting(Builder $query, string $sortBy = 'created_at', string $direction = 'desc'): Builder
    {
        $allowedSorts = [
            'product_name',
            'sku',
            'base_price',
            'retail_price',
            'wholesale_price',
            'margin',
            'created_at',
            'updated_at',
            'availability_date',
            'is_active'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            return $query->orderBy($sortBy, $direction === 'asc' ? 'asc' : 'desc');
        }

        return $query->latest();
    }
}
