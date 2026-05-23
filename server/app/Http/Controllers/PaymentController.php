<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Services\PaymentService;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

use App\Http\Requests\Payment\PaymentCreationRequest;
use App\Http\Requests\Payment\PaymentDeleteRequest;
use App\Http\Requests\Payment\PaymentUpdateRequest;

class PaymentController extends Controller
{
  public function __construct(
    private PaymentService $service
  ) {}


  public function index(): JsonResponse
  {
    $pl = $this->service->getAllWithRelationsPaginated();
    return response()->json($pl);
  }

  public function store(Request $request): JsonResponse
  {
    $pl = $this->service->create($request->all());
    return response()->json($pl, 201);
  }


  public function update(PaymentUpdateRequest $request, string $id): JsonResponse
  {
    $data = $request->validated();
    $product = $this->service->update($id, $data);

    if (!$product) {
      return response()->json([
        'success' => false,
        'message' => 'Payment not found'
      ], 404);
    }

    return response()->json($product);
  }

  /**
   * Delete single product
   * Получаем ID из route параметра {id}
   */
  public function destroy(PaymentDeleteRequest $request): JsonResponse
  {
    $data = $request->validated();

    $deleted = $this->service->delete($data);

    if (!$deleted) {
      return response()->json([
        'success' => false,
        'message' => 'Payment not found'
      ], 404);
    }

    return response()->json(null, 204);
  }

  public function show(string $id): View
  {
    $product = $this->service->getOneById($id);

    return view('product.view', [
      'product' => $product
    ]);
  }

  public function updateStatus(PaymentUpdateRequest $request, string $id): JsonResponse
  {

    $data = $request->validated();
    $product = $this->service->update($id, $data);

    if (!$product) {
      return response()->json([
        'success' => false,
        'message' => 'Payment not found'
      ], 404);
    }

    return response()->json($product);
  }

  public function showReport(string $id): View
  {
    $report = $this->service->getReportById($id);
    return view('report.view', [
      'report' => $report
    ]);
  }
}
