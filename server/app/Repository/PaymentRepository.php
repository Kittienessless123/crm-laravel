<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Payment;

class PaymentRepository extends BaseRepository
{
  protected array $defaultRelations = ['transaction'];

  public function __construct(Payment $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }


  //get status by payment id

  // get status by tr id 

  
}
