<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCityJob;
use App\Models\City;
use App\Models\Province;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CityController extends Controller
{
    public function store()
    {
        try {
            $provinces = Province::get();

            foreach ($provinces as $province) {
                CreateCityJob::dispatch($province);
            }
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function show(City $city): View
    {
        $city->load('districts');

        return \view('city.show', compact('city'));
    }
}
