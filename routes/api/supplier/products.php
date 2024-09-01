<?php

use App\Http\Controllers\Supplier\ProductsController;
use Illuminate\Support\Facades\Route;

Route::name('product.')->middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductsController::class, 'get'])->name('get');
});
