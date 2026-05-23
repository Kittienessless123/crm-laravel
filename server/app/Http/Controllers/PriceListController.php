<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Services\PriceListService;
use App\Models\PriceList;
use Illuminate\Http\JsonResponse;


class PriceListController extends Controller
{
  public function __construct(
        private PriceListService $service
    ) {}


    public function index(): JsonResponse
    {
        $products = $this->service->getAll();
        return response()->json($products);
    }
}
