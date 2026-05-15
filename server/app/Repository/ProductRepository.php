<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Product;

class ProductRepository
{
  protected Product $model;

  public function __construct(Product $model)
  {
    $this->model = $model;
  }


  public array $products = [];

  public function getAllWithPagination(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->latest()
      ->paginate($perPage);
  }

  public function getAll(): Collection
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->latest()
      ->get();
  }
  public function findById(string $id): ?Product
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->find($id);
  }

  public function findBySku(string $sku): ?Product
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('sku', $sku)
      ->first();
  }

  public function findByProductId(string $productId): ?Product
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('product_id', $productId)
      ->first();
  }
  public function create(array $data): Product
  {
    return $this->model->create($data);
  }

  public function createMany(array $productsData): Collection
  {
    $products = [];

    foreach ($productsData as $data) {
      $products[] = $this->model->create($data);
    }

    return new Collection($products);
  }

  public function update(string $id, array $data): ?Product
  {
    $product = $this->model->find($id);

    if ($product) {
      $product->update($data);
      return $product->fresh(['category', 'unit', 'seller', 'supplier']);
    }

    return null;
  }

  public function delete(string $id): bool
  {
    $product = $this->model->find($id);

    if ($product) {
      return $product->delete();
    }

    return false;
  }

  public function deleteMany(array $ids): int
  {
    return $this->model->whereIn('id', $ids)->delete();
  }

  public function updateOrCreateByProductId(array $attributes, array $values): Product
  {
    return $this->model->updateOrCreate(
      ['product_id' => $attributes['product_id']],
      array_merge($attributes, $values)
    );
  }

  public function getByCategory(string $categoryId, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('category_id', $categoryId)
      ->latest()
      ->paginate($perPage);
  }

  public function getActiveProducts(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('is_active', true)
      ->where('is_available', true)
      ->latest()
      ->paginate($perPage);
  }
}
