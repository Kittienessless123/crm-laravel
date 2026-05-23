<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Supplier extends Model
{
    use HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',          // Внешний ID из прайса поставщика
        'name'
    ];

    protected function casts(): array
    {
        return [];
    }

    public function scopeWithDefaults(Builder $query): Builder
    {
        return $query->with(['products']);
    }

    /**
     * Загрузить связи динамически
     */
    public function scopeWithRelations(Builder $query, array|string $relations): Builder
    {
        if (is_string($relations)) {
            return $query->with(explode(',', $relations));
        }

        return $query->with($relations);
    }

    /**
     * Универсальный фильтр
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('product_id', 'like', "%{$search}%");
                });
            })
            ->when($filters['product_id'] ?? null, fn(Builder $q, string $v) => $q->where('product_id', $v))
            ->when($filters['created_at_from'] ?? null, fn(Builder $q, string $v) => $q->where('created_at', '>=', $v))
            ->when($filters['created_at_to'] ?? null, fn(Builder $q, string $v) => $q->where('created_at', '<=', $v))
            ->when($filters['updated_at_from'] ?? null, fn(Builder $q, string $v) => $q->where('updated_at', '>=', $v))
            ->when($filters['updated_at_to'] ?? null, fn(Builder $q, string $v) => $q->where('updated_at', '<=', $v));
    }

    /**
     * Сортировка
     */
    public function scopeApplySorting(Builder $query, string $sortBy = 'created_at', string $direction = 'desc'): Builder
    {
        $allowedSorts = [
            'name',
            'product_id',
            'created_at',
            'updated_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            return $query->orderBy($sortBy, $direction === 'asc' ? 'asc' : 'desc');
        }

        return $query->latest();
    }
}
