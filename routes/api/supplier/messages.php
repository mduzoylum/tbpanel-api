<?php

use App\Http\Controllers\Supplier\MessagesController;
use Illuminate\Support\Facades\Route;


Route::prefix('messages')->name('messages.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/get', [MessagesController::class, 'get'])->name('get');
    Route::post('/set', [MessagesController::class, 'send'])->name('set');
});
