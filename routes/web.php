<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::group(['prefix' => 'province'], function () {
    Route::post('/store', [ProvinceController::class, 'store'])->name('province.store');
    Route::get('/{province:code}', [ProvinceController::class, 'show'])->name('province.show');
});

Route::group(['prefix' => 'city'], function () {
    Route::post('/store', [CityController::class, 'store'])->name('city.store');
    Route::get('/{city:code}', [CityController::class, 'show'])->name('city.show');
});

Route::group(['prefix' => 'district'], function () {
    Route::post('/store', [DistrictController::class, 'store'])->name('district.store');
    Route::get('/{district:code}', [DistrictController::class, 'show'])->name('district.show');
});

Route::group(['prefix' => 'school'], function () {
    Route::post('/{city:code}/store', [SchoolController::class, 'store'])->name('school.store');
    Route::post('/store-all', [SchoolController::class, 'storeAll'])->name('school.store-all');
});