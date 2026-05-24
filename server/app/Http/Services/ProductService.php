<?php

namespace App\Http\Services;

use App\Http\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


class ProductService extends BaseService
{
  protected $repo; // Теперь работает!

  public function __construct(ProductRepository $repo)
  {
    parent::__construct($repo);
  }

  // Специфичные методы для Product

  public function getBySku(string $sku): ?Product
  {
    return $this->repo->getBySku($sku);
  }

  public function getByProductId(string $productId): ?Product
  {
    return $this->repo->getByProductId($productId);
  }

  public function getBuyable(array $filters = [], string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getActiveProducts(
      sortBy: $sortBy,
      direction: $direction,
      perPage: $perPage
    );
  }

  public function getByCategory(string $categoryId, string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getByCategoryPaginated(
      categoryId: $categoryId,
      sortBy: $sortBy,
      direction: $direction,
      perPage: $perPage
    );
  }
  
  public function createBulk(array $productsData): Collection
  {
    return $this->repo->createMany($productsData);
  }

  public function search(string $term, string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->searchPaginated(
      term: $term,
      sortBy: $sortBy,
      direction: $direction,
      perPage: $perPage
    );
  }
}
