<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Supplier;

class SupplierRepository extends BaseRepository
{
  protected static  array $defaultRelations = [];

  public function __construct(Supplier $model)
  {
    parent::__construct($model, $this->defaultRelations);
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
