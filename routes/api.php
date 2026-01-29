<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScraperController;

Route::get('/', [ScraperController::class, 'show']);

Route::get('/info/{date}/{source?}', [ScraperController::class, 'getInfo']);

Route::fallback(function () {
    return response()->json([
        'error' => 'recurso no existente'
    ], 404);
});
