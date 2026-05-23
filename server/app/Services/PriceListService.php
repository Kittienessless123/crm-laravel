<?php

namespace App\Http\Services;

use App\Http\Repositories\PriceListRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PriceListService extends BaseService
{
  protected PriceListRepository $repo; // Теперь работает!

  public function __construct(PriceListRepository $repo)
  {
    parent::__construct($repo);
  }

  
}
