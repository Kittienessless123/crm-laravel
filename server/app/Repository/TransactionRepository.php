<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
  protected array $defaultRelations = ['seller', 'payments', 'items'];

  public function __construct(Transaction $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }

  //get by seller id 

  //get by transaction number

  //get by sku

  //get by currency 

  //get by payment type 

  //get by transaction date first or last or first-last 

  //get is pre payment

  //get by sku 

  //get by payment_received_date first or last or first-last 

  

}