<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;


Route::name('product.')->group(function () {
    Route::any('/products', [\App\Http\Controllers\Admin\ProductController::class, 'get'])->name('get');
});
