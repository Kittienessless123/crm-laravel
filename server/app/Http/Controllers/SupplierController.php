<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\SupplierCreationRequest;
use App\Http\Requests\Supplier\SupplierUpdateRequest;
use App\Http\Requests\Supplier\SupplierDeleteRequest;
use App\Http\Services\SupplierService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\View\View;

class SupplierController extends Controller
{
  public function __construct(
    private SupplierService $supplierService
  ) {}
  public function index(Request $request): JsonResponse
  {
    $suppliers = $this->supplierService->getAll();
    return response()->json($suppliers);
  }

  public function store(SupplierCreationRequest $request): JsonResponse
  {
    $supplier = $this->supplierService->create($request->validated());
    return response()->json($supplier, 201);
  }

  public function update(SupplierUpdateRequest $request, string $id): JsonResponse
  {
    $data = $request->validated();
    $supplier = $this->supplierService->update($id, $data);

    if (!$supplier) {
      return response()->json([
        'success' => false,
        'message' => 'Supplier not found'
      ], 404);
    }

    return response()->json($supplier);
  }

  public function destroy(SupplierDeleteRequest $request, string $id): JsonResponse
  {
    $data = $request->validated();

    $deleted = $this->supplierService->delete($data);

    if (!$deleted) {
      return response()->json([
        'success' => false,
        'message' => 'Supplier not found'
      ], 404);
    }

    return response()->json(null, 204);
  }

  public function show(string $id): View
  {
    $supplier = $this->supplierService->getOneById($id);

    return view('supplier.view', [
      'supplier' => $supplier
    ]);
  }
}
