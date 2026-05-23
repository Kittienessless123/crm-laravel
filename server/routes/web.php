<?php

use App\Http\Controllers\PriceListController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SupplierController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');


    Route::prefix('products')->name('products.')->group(function () {
        // GET /products - все продукты
        Route::get('/', [ProductController::class, 'index'])->name('index');

        // GET /products/active - активные продукты (с пагинацией)
        Route::get('/active', [ProductController::class, 'getActive'])->name('active');

        // GET /products/category/{cat_id} - по категории
        Route::get('/category/{cat_id}', [ProductController::class, 'getByCategory'])->name('by-category');

        // POST /products - создать один
        Route::post('/', [ProductController::class, 'store'])->name('store');

        // POST /products/bulk - массовое создание
        Route::post('/bulk', [ProductController::class, 'storeBulk'])->name('store.bulk');

        // DELETE /products/bulk - массовое удаление
        Route::delete('/bulk', [ProductController::class, 'destroyMany'])->name('destroy.bulk');

        // GET /products/{id} - получить один
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');

        // PUT /products/{id} - обновить
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');

        // DELETE /products/{id} - удалить один
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/sellers')->name('sellers.')->group(function () {
        Route::get('/', [SellerController::class, 'index'])->name('index');
        Route::post('/', [SellerController::class, 'store'])->name('store');
        Route::get('/{id}', [SellerController::class, 'show'])->name('show');
        Route::delete('/{id}', [SellerController::class, 'destroy'])->name('destroy');
        Route::put('/{id}', [SellerController::class,    'update'])->name('update');
    });

    Route::prefix('/suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::put('/{id}', [SupplierController::class,    'update'])->name('update');
    });

    Route::prefix('/pricelist')->name('pricelist.')->group(function () {
        Route::get('/', [PriceListController::class, 'index'])->name('index');
        Route::post('/', [PriceListController::class, 'store'])->name('store');
    });
});
require __DIR__ . '/settings.php';
