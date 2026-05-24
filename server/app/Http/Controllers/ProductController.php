<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\Product\ProductCreationRequest;
use App\Http\Requests\Product\ProductDeleteRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Category;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Get all products
     */
    public function index(Request $request)
    {
        $products = $this->productService->getAllWithPagination(
            filters: $request->only(["search", "category_id",]),
            sortBy: $request->input("sort_by", "created_at"),
            direction: $request->input("direction", "desc"),
            perPage: $request->input("per_page", 15)

        );

        $categories = Category::where('is_active', true)->get();

        return Inertia::render('products/index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id', 'sort_by', 'direction']),
        ]);
    }

    /**
     * Get products by category (with pagination)
     */
    public function getByCategory(Request $request, string $cat_id): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $products = $this->productService->getByCategory($cat_id, $perPage);
        return response()->json($products);
    }

    /**
     * Get active products (with pagination)
     */
    public function getActive(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $products = $this->productService->getBuyable($perPage);
        return response()->json($products);
    }

    public function store(ProductCreationRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        return response()->json($product, 201);
    }

    /**
     * Create multiple products (bulk)
     */
    public function storeBulk(ProductCreationRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Ожидаем массив products: ['products' => [...]] или просто массив
        $productsData = $data['products'] ?? $data;

        $products = $this->productService->createBulk($productsData);
        return response()->json($products, 201);
    }

    /**
     * Update product
     * Получаем ID из route параметра {id}
     */
    public function update(ProductUpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $product = $this->productService->update($id, $data);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json($product);
    }

    /**
     * Delete single product
     * Получаем ID из route параметра {id}
     */
    public function destroy(ProductDeleteRequest $request)
    {
        $data = $request->validated();

        $deleted = $this->productService->delete($data);

        return redirect()->back()->with('success', 'Товар удален');
    }

    /**
     * Delete multiple products
     * Получаем массив IDs из тела запроса
     */
    public function destroyMany(ProductDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $ids = $data->input('ids');

        // Вариант 2: ids как JSON массив
        if (!$ids && $request->isJson()) {
            $ids = $request->json()->all();
        }

        // Вариант 3: ids как строка через запятую
        if (!$ids && $request->has('ids_string')) {
            $ids = explode(',', $request->input('ids_string'));
        }

        // Проверяем, что ids - массив
        if (!$ids || !is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or empty ids array'
            ], 400);
        }

        $deleted = $this->productService->deleteMany($ids);

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} products",
            'deleted_count' => $deleted
        ], 200);
    }


    public function show(string $id)
    {
        $product = $this->productService->getOneById($id);
        if (!$product) abort(404);
        return Inertia::render('products/view', [
            'product' => $product
        ]);
    }
}
