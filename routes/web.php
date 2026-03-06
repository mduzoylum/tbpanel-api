<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'TBPanel API',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
});