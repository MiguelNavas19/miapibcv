<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ExchangeController;

Route::middleware('throttle:10,1')->group(function () {
    Route::get('/', [ExchangeController::class, 'index']);
});
