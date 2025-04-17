<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProvinceController;
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
