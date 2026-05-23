<?php

namespace App\Http\Services;

use App\Http\Repositories\SellerRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class SellerService extends BaseService
{
  protected SellerRepository $repo;

  public function __construct(SellerRepository $repo)
  {
    parent::__construct($repo);
  }

  public function getSellerByNamePaginated(string $name, int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getSellerByNamePaginated($name, $perPage);
  }
}
