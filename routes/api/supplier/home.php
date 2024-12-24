<?php

use App\Http\Controllers\Supplier\HomeController;
use Illuminate\Support\Facades\Route;

Route::name('home.')->middleware('auth:sanctum')->group(function () {
    Route::get('/home', HomeController::class)->name('list');
});
