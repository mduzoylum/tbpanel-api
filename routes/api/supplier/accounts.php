<?php

use App\Http\Controllers\Supplier\AccountsController;
use Illuminate\Support\Facades\Route;

Route::name('account.')->middleware('auth:sanctum')->group(function () {
    Route::get('/accounts', AccountsController::class)->name('list');
});
