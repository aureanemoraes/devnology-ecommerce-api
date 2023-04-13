<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\ShoppingCartController;

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

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'listAllProducts']);
    Route::get('/{supplier}', [ProductController::class, 'listProductsBySupplier']);
});

Route::prefix('shopping-cart')->group(function () {
    Route::post('/{itemId}', [ShoppingCartController::class, 'addItem']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
