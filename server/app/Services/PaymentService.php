<?php

namespace App\Http\Services;

use App\Http\Repositories\PaymentRepository;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PaymentService extends BaseService
{
  protected PaymentRepository $repo;

  public function __construct(PaymentRepository $repo)
  {
    parent::__construct($repo);
  }

  public function getAllWithRelationsPaginated(int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getAllWithRelationsPaginated($perPage);
  }

  public function getReportById(int $perPage = 15): int
  {
    return 1;
  }

  public function updateStatus(string $paymentId, string $status): Payment
  {
    return $this->repo->updateStatus($paymentId, $status);
  }
}
