<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Seller;

class SellerRepository extends BaseRepository
{
  protected array $defaultRelations = [];

  public function __construct(Seller $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }


  public function getSellerByName(string $name, int $perPage = 15): ?LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('name', $name)
      ->latest()
      ->paginate($perPage);
  }
}
