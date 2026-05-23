<?php

namespace App\Http\Services;

use App\Http\Repositories\SellerRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class SellerService extends BaseService
{
  protected SellerRepository $repo; // Теперь работает!

  public function __construct(SellerRepository $repo)
  {
    parent::__construct($repo);
  }

  
}
