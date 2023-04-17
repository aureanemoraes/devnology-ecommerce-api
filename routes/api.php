<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/filters', 'getProductFiltersOptions');

    Route::get('/', 'listAllProducts');
    Route::get('/{supplier}', 'listProductsBySupplier');
    Route::get('/{supplier_name}/{product_id}', 'getProduct');

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('/orders')->controller(OrderController::class)->group(function () {
        Route::get('/', 'history');
        Route::post('/resume', 'resume');
        Route::post('/buy', 'buy');
    });
});
