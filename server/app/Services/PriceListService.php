<?php

namespace App\Http\Services;

use App\Http\Repositories\PriceListRepository;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PriceListService extends BaseService
{
  protected PriceListRepository $repo;

  public function __construct(PriceListRepository $repo)
  {
    parent::__construct($repo);
  }

  public function getAllWithRelationsPaginated(int $perPage = 15) : LengthAwarePaginator{
     return $this->repo->getAllWithRelationsPaginated($perPage);
  }


}
