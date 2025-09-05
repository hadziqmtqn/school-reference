<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\GenerateSchoolDataController;
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
    Route::post('/{province:code}/store', [CityController::class, 'generateByProvince'])->name('city.generate-by-province');
    Route::get('/{city:code}', [CityController::class, 'show'])->name('city.show');
});

Route::group(['prefix' => 'district'], function () {
    Route::post('/store', [DistrictController::class, 'store'])->name('district.store');
    Route::post('/{city:code}/store', [DistrictController::class, 'generateByCity'])->name('district.generate-by-city');
    Route::get('/{district:code}', [DistrictController::class, 'show'])->name('district.show');
});

Route::group(['prefix' => 'school'], function () {
    Route::post('/export/data/{district:code}', [SchoolController::class, 'export'])->name('school.export');
});

Route::prefix('generate-school-data')->group(function () {
    Route::post('{province:code}', [GenerateSchoolDataController::class, 'generateByProvince'])->name('generate-school-data.province');
    Route::post('{city:code}', [GenerateSchoolDataController::class, 'generateByCity'])->name('generate-school-data.city');
    Route::post('create-all-school', [GenerateSchoolDataController::class, 'createAllSchool'])->name('generate-school-data.school');
});