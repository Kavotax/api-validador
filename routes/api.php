<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\AdvancedValidationController;


Route::post('/validate/phone', [ValidationController::class, 'validatePhone']);
Route::post('/validate/email', [AdvancedValidationController::class, 'validateEmail']);



