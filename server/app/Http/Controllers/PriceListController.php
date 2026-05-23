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
use Illuminate\View\View;


class PriceListController extends Controller
{
    public function __construct(
        private PriceListService $service
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
}
