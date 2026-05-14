<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
  use HasUuids;

  protected $table = 'sellers';

  public $incrementing = false;
  protected $keyType = 'string'; // ← добавь это

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
}
