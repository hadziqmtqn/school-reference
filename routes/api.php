<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FormOfEducationController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('provinces', [ProvinceController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);
Route::get('districts', [DistrictController::class, 'index']);
Route::get('form-of-education', [FormOfEducationController::class, 'index']);
Route::get('schools', [SchoolController::class, 'index']);
