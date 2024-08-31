<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;


Route::name('user.')->group(function () {
    Route::any('/users', [\App\Http\Controllers\Admin\UserController::class, 'get'])->name('get');
});
