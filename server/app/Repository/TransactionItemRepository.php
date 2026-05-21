<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\TransactionItem;

class TransactionItemRepository extends BaseRepository
{
  protected array $defaultRelations = ['transaction', 'product'];

  public function __construct(TransactionItem $model)
  {
    parent::__construct($model, self::$defaultRelations);
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

  //todo 
  //where options

  public function getItemsFiltered(string $whereOp, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->where('sku', $whereOp)
      ->latest()
      ->paginate($perPage);
  }
}
