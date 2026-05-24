<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\TransactionItem;

class TransactionItemRepository extends BaseRepository
{
  protected static  array $defaultRelations = ['transaction', 'product'];

  public function __construct(TransactionItem $model)
  {
    parent::__construct($model, $this->defaultRelations);
  }

  public function getItemByProductId(string $productId): ?TransactionItem
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('product_id', $productId)
      ->first();
  }

  public function getItemBySku(string $sku): ?TransactionItem
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('sku', $sku)
      ->first();
  }

  public function getSortedPaginated(string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->defaultRelations)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getFilteredAndSortedPaginated(array $filters, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->defaultRelations)
      ->filter($filters)
      ->applySorting($sortBy, $direction)
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
