<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\ProvinceController;
use Illuminate\Support\Facades\Route;

Route::get('province', [ProvinceController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);
