<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
  protected  array $defaultRelations = [];

  public function __construct(Category $model)
  {
    parent::__construct($model, $this->defaultRelations);
  }


}
