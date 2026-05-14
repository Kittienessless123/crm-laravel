<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',          // Внешний ID из прайса поставщика
        'product_name',        // Название товара
        'sku',                 // Артикул/SKU
        'description',         // Описание
        'category',            // Категория (стройматериалы, пиломатериалы и т.д.)
        'unit',                // Единица измерения (шт, м3, пог.м, кг)
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
        'availability_date',   // Дата готовности (май-июнь, Сентябрь)
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
            'availability_date' => 'date',
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
}