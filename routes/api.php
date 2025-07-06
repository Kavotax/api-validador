<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;

Route::middleware(App\Http\Middleware\ApiKeyMiddleware::class)->group(function () {
    Route::post('/validate/email', [ValidationController::class, 'validateEmail']);
    Route::post('/validate/phone', [ValidationController::class, 'validatePhone']);
});
