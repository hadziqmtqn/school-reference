<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProvinceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::group(['prefix' => 'province'], function () {
    Route::post('/store', [ProvinceController::class, 'store'])->name('province.store');
});