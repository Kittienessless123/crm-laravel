<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\PriceList;

class PriceListRepository extends BaseRepository
{
  protected array $defaultRelations = ['supplier', 'product'];

  public function __construct(PriceList $model)
  {
    parent::__construct($model, self::$defaultRelations);
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
}
