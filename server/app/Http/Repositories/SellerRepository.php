<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Seller;

class SellerRepository extends BaseRepository
{
  protected  static  array $defaultRelations = [];

  public function __construct(Seller $model)
  {
    parent::__construct($model, $this->defaultRelations);
  }

  public function getSellerByNamePaginated(string $name, int $perPage = 15): ?LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('name', $name)
      ->latest()
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
