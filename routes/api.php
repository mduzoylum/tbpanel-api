<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckPermission;

use App\Http\Controllers\AuthController;

Route::get('/test', function () {
    return response()->json(['message' => 'Bismillah!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

//check permission
Route::middleware(['auth:sanctum','check.permission:view-dashboard'])->get('/dashboard', function () {
    return response()->json(['message' => 'Dashboard']);
});
