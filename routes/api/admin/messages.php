<?php

use App\Http\Controllers\Admin\MessagesController;
use Illuminate\Support\Facades\Route;


Route::prefix('messages')->name('messages.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/get/{user}', [MessagesController::class, 'get'])->name('get');
    Route::post('/set/{user}', [MessagesController::class, 'send'])->name('set');

    Route::get('/user-list', [MessagesController::class, 'userList'])->name('user-list');
});
