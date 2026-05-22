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

  public function getBySellerIdPaginated(string $sellerId, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('seller_id', $sellerId)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getBySellerId(string $sellerId): Collection
  {
    return $this->model
      ->withDefaults()
      ->where('seller_id', $sellerId)
      ->latest('creation_date')
      ->get();
  }

  public function getByTransactionNumber(string $transactionNumber): ?Transaction
  {
    return $this->model
      ->withDefaults()
      ->where('transaction_number', $transactionNumber)
      ->first();
  }

  public function getBySkuPaginated(string $sku, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('sku', $sku)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getBySku(string $sku): Collection
  {
    return $this->model
      ->withDefaults()
      ->where('sku', $sku)
      ->latest('creation_date')
      ->get();
  }

  public function getByCurrencyPaginated(string $currency, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('currency', strtoupper($currency))
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByCurrency(string $currency): Collection
  {
    return $this->model
      ->withDefaults()
      ->where('currency', strtoupper($currency))
      ->latest('creation_date')
      ->get();
  }

  public function getByPaymentTypePaginated(string $paymentType, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('payment_type', $paymentType)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByPaymentType(string $paymentType): Collection
  {
    return $this->model
      ->withDefaults()
      ->where('payment_type', $paymentType)
      ->latest('creation_date')
      ->get();
  }

  public function getByTransactionDatePaginated(string $date, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->whereDate('transaction_date', $date)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByTransactionDateFrom(string $dateFrom, string $sortBy = 'transaction_date', string $direction = 'asc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('transaction_date', '>=', $dateFrom)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByTransactionDateTo(string $dateTo, string $sortBy = 'transaction_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('transaction_date', '<=', $dateTo)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByTransactionDatePeriodPaginated(string $dateFrom, string $dateTo, string $sortBy = 'transaction_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('transaction_date', '>=', $dateFrom)
      ->where('transaction_date', '<=', $dateTo)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByPaymentReceivedDatePaginated(string $date, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->whereDate('payment_received_date', $date)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }


  public function getByPaymentReceivedDateFrom(string $dateFrom, string $sortBy = 'payment_received_date', string $direction = 'asc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('payment_received_date', '>=', $dateFrom)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByPaymentReceivedDateTo(string $dateTo, string $sortBy = 'payment_received_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('payment_received_date', '<=', $dateTo)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByPaymentReceivedDatePeriodPaginated(string $dateFrom, string $dateTo, string $sortBy = 'payment_received_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('payment_received_date', '>=', $dateFrom)
      ->where('payment_received_date', '<=', $dateTo)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getPrepaymentPaginated(string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('is_prepayment', true)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getWithoutPrepaymentPaginated(string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('is_prepayment', false)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getByStatusPaginated(string $status, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where('status', $status)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getFilteredPaginated(array $filters, int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->filter($filters)
      ->latest('creation_date')
      ->paginate($perPage);
  }


  public function getSortedPaginated(string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }

  public function getFilteredAndSortedPaginated(array $filters, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->filter($filters)
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }


  public function searchPaginated(string $term, string $sortBy = 'creation_date', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->withDefaults()
      ->where(function ($query) use ($term) {
        $query->where('transaction_number', 'like', "%{$term}%")
          ->orWhere('description', 'like', "%{$term}%")
          ->orWhere('invoice_number', 'like', "%{$term}%")
          ->orWhere('sku', 'like', "%{$term}%");
      })
      ->applySorting($sortBy, $direction)
      ->paginate($perPage);
  }
}
