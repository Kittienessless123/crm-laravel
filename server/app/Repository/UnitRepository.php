<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Unit;

class UnitRepository extends BaseRepository
{
  protected array $defaultRelations = [];

  public function __construct(Unit $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }
}
