<?php

namespace App\Http\Services;

use App\Http\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
  public function __construct(
    private ProductRepository $productRepo
  ) {}


  public Product $product;

  public function getOneByProductId(int $pruduct_id): Product
  {
    return $this->productRepo->findByProductId($pruduct_id);
  }

  public function create(array $data): Product
  {
    return $this->productRepo->create($data);
  }

  public function update(string $pruduct_id, array $data): Product
  {
    return $this->productRepo->update($pruduct_id, $data);
  }

  public function delete(string $id): bool
  {
    return  $this->productRepo->delete($id);
  }

  public function getAll(): Collection
  {
    return $this->productRepo->getAll();
  }

  public function createBulk(array $data): Collection
  {
    return $this->productRepo->createMany($data);
  }

  public function deleteBulk(array $ids): int
  {
    return $this->productRepo->deleteMany($ids);
  }

  public function getProductsByCategory(string $cat_id, int $perPage = 15): LengthAwarePaginator
  {
    return $this->productRepo->getByCategory($cat_id, $perPage);
  }

  public function getActiveProducts(int $perPage = 15): LengthAwarePaginator
  {
    return $this->productRepo->getActiveProducts($perPage);
  }
}
