<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;


Route::name('supplier.')->group(function () {
    Route::any('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'get'])->name('get');
});
