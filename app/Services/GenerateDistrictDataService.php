<?php

namespace App\Services;

use App\Http\Requests\School\CreateAllRequest;
use App\Jobs\CreateDistrictJob;
use App\Models\City;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class GenerateDistrictDataService
{
    public function createByCity(City $city): RedirectResponse
    {
        try {
            CreateDistrictJob::dispatch($city);
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function createByAll(CreateAllRequest $request): RedirectResponse
    {
        try {
            if ($request->input('token') != 'HAbesar2') {
                return redirect()->back()->with('error', 'Token tidak valid');
            }

            $cities = City::all();

            foreach ($cities as $city) {
                CreateDistrictJob::dispatch($city);
            }
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }
}
