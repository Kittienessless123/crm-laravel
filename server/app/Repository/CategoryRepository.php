<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\PriceList;

class PriceListRepository extends BaseRepository
{
  protected array $defaultRelations = [];

  public function __construct(PriceList $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }


}
