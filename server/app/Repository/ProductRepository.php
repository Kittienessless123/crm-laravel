<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
  protected array $defaultRelations = ['category', 'unit', 'seller', 'supplier'];

  public function __construct(Product $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }

  public function getBySku(string $sku): ?Product
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('sku', $sku)
      ->first();
  }

  public function getByProductId(string $productId): ?Product
  {
    return $this->model
      ->with(['category', 'unit', 'seller', 'supplier'])
      ->where('product_id', $productId)
      ->first();
  }

  public function createMany(array $productsData): Collection
  {
    $products = [];

    foreach ($productsData as $data) {
      $products[] = $this->model->create($data);
    }

    return new Collection($products);
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

  public function getByCategoryPaginated(string $categoryId, string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('category_id', $categoryId)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getActiveProducts(string $sortBy = 'created_at', string $direction = 'desc',  int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('is_active', true)
      ->where('is_available', true)
      ->latest()
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getSortedPaginated(string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->defaultRelations)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getFilteredPaginated(array $filters, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->filter('product_id', $filters)
      ->latest()
      ->paginate($perPage);
  }

  public function searchPaginated(string $term, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->defaultRelations)
      ->where(function ($query) use ($term) {
        $query->where('name', 'like', "%{$term}%")
          ->orWhere('contact_info', 'like', "%{$term}%");
      })
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }
}
