<?php

namespace App\Services;

use App\Http\Requests\School\CreateAllRequest;
use App\Jobs\GenerateSchools\GenerateSchoolsForCityJob;
use App\Jobs\GenerateSchools\GenerateSchoolsForProvinceJob;
use App\Models\City;
use App\Models\Province;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class GenerateSchoolDataService
{
    public function generateByProvince(CreateAllRequest$request, Province $province): RedirectResponse
    {
        try {
            if ($request->input('token') !== 'HAbesar2') {
                return redirect()->back()->with('error', 'Token tidak valid');
            }

            GenerateSchoolsForProvinceJob::dispatch($province->id);

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function generateByCity(CreateAllRequest $request, City $city): RedirectResponse
    {
        try {
            if ($request->input('token') !== 'HAbesar2') {
                return redirect()->back()->with('error', 'Token tidak valid');
            }

            GenerateSchoolsForCityJob::dispatch(null, $city->id);

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function createAllSchool(CreateAllRequest $request): RedirectResponse
    {
        try {
            if ($request->input('token') !== 'HAbesar2') {
                return redirect()->back()->with('error', 'Token tidak valid');
            }

            GenerateSchoolsForProvinceJob::dispatch();

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }
}
