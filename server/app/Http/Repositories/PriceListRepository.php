<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\PriceList;

class PriceListRepository extends BaseRepository
{
  protected static  array $defaultRelations = ['supplier', 'product'];

  public function __construct(PriceList $model)
  {
    parent::__construct($model, $this->defaultRelations);
  }

  public function getAllWithRelationsPaginated(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->latest()
      ->paginate($perPage);
  }

  public function getActiveProductsPaginate(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('is_active', true)
      ->latest()
      ->paginate($perPage);
  }

  public function getProductsAllBySupplierPaginate(string $supplierId, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('supplier_id', $supplierId)
      ->latest()
      ->paginate($perPage);
  }

  public function getProductsActiveBySupplierPaginate(string $supplierId, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('supplier_id', $supplierId)
      ->where('is_active', true)
      ->latest()
      ->paginate($perPage);
  }

  public function getSupplierByProductPaginate(string $productId, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('product_id', $productId)
      ->latest()
      ->paginate($perPage);
  }


  public function getPriceListFilteredPaginated(array $filters, int $perPage = 15): LengthAwarePaginator
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
