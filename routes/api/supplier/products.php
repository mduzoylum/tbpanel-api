<?php

use App\Http\Controllers\Supplier\ProductsController;
use Illuminate\Support\Facades\Route;


Route::prefix('products')->name('products.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/get', [ProductsController::class, 'get'])->name('get');
});
