<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    require base_path('routes/api/admin/auth.php');
});

Route::prefix('supplier')->name('supplier.')->group(function () {
    require base_path('routes/api/supplier/auth.php');
});
