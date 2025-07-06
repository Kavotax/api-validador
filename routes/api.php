<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ValidationController;

Route::post('/validate/email', [ValidationController::class, 'validateEmail']);
Route::post('/validate/phone', [ValidationController::class, 'validatePhone']);