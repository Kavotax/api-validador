<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\AdvancedValidationController;

Route::middleware(App\Http\Middleware\ApiKeyMiddleware::class)->group(function () {
    Route::post('/validate/phone', [ValidationController::class, 'validatePhone']);
    Route::post('/validate/email', [AdvancedValidationController::class, 'validateEmail']);
});


