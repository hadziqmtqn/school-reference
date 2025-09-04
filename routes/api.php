<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FormOfEducationController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RefCloud\CloudCityController;
use App\Http\Controllers\RefCloud\CloudDistrictController;
use App\Http\Controllers\RefCloud\CloudProvinceController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\VillageController;
use Illuminate\Support\Facades\Route;

Route::get('provinces', [ProvinceController::class, 'index']);
Route::get('cities', [CityController::class, 'index']);
Route::get('districts', [DistrictController::class, 'index']);
Route::get('villages', [VillageController::class, 'index']);
Route::get('form-of-education', [FormOfEducationController::class, 'index']);
Route::get('schools', [SchoolController::class, 'index']);

Route::get('cloud-province', [CloudProvinceController::class, 'index']);
Route::get('cloud-city', [CloudCityController::class, 'index']);
Route::get('cloud-district', [CloudDistrictController::class, 'index']);