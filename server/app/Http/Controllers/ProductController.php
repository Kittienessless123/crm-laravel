<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Get all products
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getAll();
        return response()->json($products);
    }

    /**
     * Get products by category (with pagination)
     */
    public function getByCategory(Request $request, string $cat_id): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $products = $this->productService->getProductsByCategory($cat_id, $perPage);
        return response()->json($products);
    }

    /**
     * Get active products (with pagination)
     */
    public function getActive(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $products = $this->productService->getActiveProducts($perPage);
        return response()->json($products);
    }

    /**
     * Get single product by ID
     */
    public function show(string $id): JsonResponse
    {
        $product = $this->productService->getOneByProductId($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json($product);
    }

    /**
     * Create single product
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $product = $this->productService->create($data);
        return response()->json($product, 201);
    }

    /**
     * Create multiple products (bulk)
     */
    public function storeBulk(Request $request): JsonResponse
    {
        $data = $request->all();
        
        // Ожидаем массив products: ['products' => [...]] или просто массив
        $productsData = $data['products'] ?? $data;
        
        $products = $this->productService->createBulk($productsData);
        return response()->json($products, 201);
    }

    /**
     * Update product
     * Получаем ID из route параметра {id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->all();
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
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->productService->delete($id);
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json(null, 204);
    }

    /**
     * Delete multiple products
     * Получаем массив IDs из тела запроса
     */
    public function destroyMany(Request $request): JsonResponse
    {
        // Вариант 1: ids как массив в теле запроса
        $ids = $request->input('ids');
        
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
        
        $deleted = $this->productService->deleteBulk($ids);
        
        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} products",
            'deleted_count' => $deleted
        ], 200);
    }

    /**
     * Show product view (for Blade template, если нужно)
     */
    public function showView(string $id): View
    {
        $product = $this->productService->getOneByProductId($id);
        
        return view('product.view', [
            'product' => $product
        ]);
    }
}