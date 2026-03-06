<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'TBPanel API',
        'version' => '1.0.1',
        'timestamp' => now()->toDateTimeString(),
    ]);
});
