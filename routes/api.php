<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Bismillah!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//check permission
Route::middleware(['auth:sanctum', 'check.permission:view-dashboard'])->get('/dashboard', function () {
    return response()->json(['message' => 'Dashboard']);
});


/**
 * Auth routes
 */

Route::prefix('admin')->name('admin.')->group(function () {

    $routeFiles = glob(base_path('routes/api/admin/') . '*.php');

    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
});



/**
 * Supplier routes
 */

Route::prefix('supplier')->name('supplier.')->group(function () {

    $routeFiles = glob(base_path('routes/api/supplier/') . '*.php');

    foreach ($routeFiles as $routeFile) {
        require $routeFile;
    }
});
