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

  public function getAllWithRelationsPaginated(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->latest()
      ->paginate($perPage);
  }

  //change status 
  public function updateStatus(string $paymentId, string $status): ?Payment
  {
    $paymentToUpdate = $this->model->find($paymentId);
    if ($paymentToUpdate) {
      $paymentToUpdate->password_hash($status);
      $paymentToUpdate->save();
    }

    return $paymentToUpdate;
  }

  public function getPaymentFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->filter('product_id', $filters)
      ->latest()
      ->paginate($perPage);
  }

  public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
  {
    return $this->getPaymentFiltered(['status' => $status], $perPage);
  }

  public function getPending(int $perPage = 15): LengthAwarePaginator
  {
    return $this->getByStatus('pending', $perPage);
  }

  public function getCompleted(int $perPage = 15): LengthAwarePaginator
  {
    return $this->getByStatus('completed', $perPage);
  }

  public function getFailed(int $perPage = 15): LengthAwarePaginator
  {
    return $this->getByStatus('failed', $perPage);
  }

  public function getByTransaction(string $transactionId): Collection
  {
    return $this->model
      ->with($this->defaultRelations)
      ->filter(['transaction_id' => $transactionId])
      ->latest('payment_date')
      ->get();
  }

  public function getByGateway(string $gateway, int $perPage = 15): LengthAwarePaginator
  {
    return $this->getPaymentFiltered(['payment_gateway' => $gateway], $perPage);
  }

  public function getByDateRange(string $from, string $to, int $perPage = 15): LengthAwarePaginator
  {
    return $this->getPaymentFiltered([
      'payment_date_from' => $from,
      'payment_date_to' => $to,
    ], $perPage);
  }

  public function getByAmountRange(float $min, float $max, int $perPage = 15): LengthAwarePaginator
  {
    return $this->getPaymentFiltered([
      'amount_min' => $min,
      'amount_max' => $max,
    ], $perPage);
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
