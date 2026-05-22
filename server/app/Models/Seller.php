<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Seller extends Model
{
  use HasUuids;

  protected $table = 'sellers';

  public $incrementing = false;
  protected $keyType = 'string';

  const CREATED_AT = 'creation_date';
  const UPDATED_AT = 'updated_date';

  protected $fillable = [
    'name',
    'contact_info',
    'registration_date',
  ];

  protected function casts(): array
  {
    return [
      'registration_date' => 'datetime',
    ];
  }

  public function transactions(): HasMany
  {
    return $this->hasMany(Transaction::class);
  }

  public function scopeFilter(Builder $query, array $filters): Builder
  {
    return $query
      ->when($filters['search'] ?? null, function (Builder $q, string $search) {
        $q->where(function (Builder $q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('contact_info', 'like', "%{$search}%");
        });
      })
      ->when($filters['registration_date'] ?? null, fn(Builder $q, string $v) => $q->whereDate('registration_date', $v))
      ->when($filters['registration_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('registration_date', '>=', $v))
      ->when($filters['registration_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('registration_date', '<=', $v))
      ->when($filters['creation_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('creation_date', '>=', $v))
      ->when($filters['creation_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('creation_date', '<=', $v))
      ->when($filters['updated_date_from'] ?? null, fn(Builder $q, string $v) => $q->where('updated_date', '>=', $v))
      ->when($filters['updated_date_to'] ?? null, fn(Builder $q, string $v) => $q->where('updated_date', '<=', $v));
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
    return $query->with(['transactions']);
  }


  public function scopeApplySorting(Builder $query, string $sortBy = 'creation_date', string $direction = 'desc'): Builder
  {
    $allowedSorts = [
      'name',
      'registration_date',
      'creation_date',
      'updated_date'
    ];

    if (in_array($sortBy, $allowedSorts)) {
      return $query->orderBy($sortBy, $direction === 'asc' ? 'asc' : 'desc');
    }

    return $query->latest('creation_date');
  }
}
