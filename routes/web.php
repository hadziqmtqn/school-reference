<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProvinceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::post('province/store', [ProvinceController::class, 'store'])->name('province.store');
Route::post('city/store', [CityController::class, 'store'])->name('city.store');