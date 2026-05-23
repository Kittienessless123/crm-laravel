<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\SellerCreationRequest;
use App\Http\Requests\Seller\SellerUpdateRequest;
use App\Http\Requests\Seller\SellerDeleteManyRequest;
use App\Http\Requests\Seller\SellerDeleteRequest;
use App\Http\Services\SellerService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\View\View;

class SellerController extends Controller
{
  public function __construct(
    private SellerService $sellerService
  ) {}
  public function index(Request $request): JsonResponse
  {
    $sellers = $this->sellerService->getAll();
    return response()->json($sellers);
  }

  public function store(SellerCreationRequest $request): JsonResponse
  {
    $seller = $this->sellerService->create($request->validated());
    return response()->json($seller, 201);
  }

  public function update(SellerUpdateRequest $request, string $id): JsonResponse
  {
    $data = $request->validated();
    $seller = $this->sellerService->update($id, $data);

    if (!$seller) {
      return response()->json([
        'success' => false,
        'message' => 'Seller not found'
      ], 404);
    }

    return response()->json($seller);
  }

  public function destroy(SellerDeleteRequest $request, string $id): JsonResponse
  {
    $data = $request->validated();

    $deleted = $this->sellerService->delete($data);

    if (!$deleted) {
      return response()->json([
        'success' => false,
        'message' => 'Seller not found'
      ], 404);
    }

    return response()->json(null, 204);
  }

  public function show(string $id): View
  {
    $seller = $this->sellerService->getOneById($id);

    return view('seller.view', [
      'seller' => $seller
    ]);
  }
}
