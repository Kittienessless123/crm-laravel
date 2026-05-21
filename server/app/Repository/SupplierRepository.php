<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Supplier;

class SupplierRepository extends BaseRepository
{
  protected array $defaultRelations = [];

  public function __construct(Supplier $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }
}
