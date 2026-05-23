<?php

namespace App\Http\Services;

use App\Http\Repositories\SupplierRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class SupplierService extends BaseService
{
  protected SupplierRepository $repo;

  public function __construct(SupplierRepository $repo)
  {
    parent::__construct($repo);
  }

}
