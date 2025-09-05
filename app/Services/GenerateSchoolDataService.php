<?php

namespace App\Services;

use App\Http\Requests\School\CreateAllRequest;
use App\Jobs\CreateSchoolJob;
use App\Jobs\DistrictLoopingJob;
use App\Models\City;
use App\Models\District;
use App\Models\FormOfEducation;
use App\Models\Province;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class GenerateSchoolDataService
{
    public function generateByProvince(Province $province): RedirectResponse
    {
        try {
            $province->loadMissing('cities.districts');

            $cities = $province->cities;

            $formOfEducations = FormOfEducation::with('educationUnit')
                ->get();

            foreach ($cities as $city) {
                $districts = $city->districts;

                foreach ($districts as $district) {
                    foreach ($formOfEducations as $formOfEducation) {
                        CreateSchoolJob::dispatch($district, $formOfEducation);
                    }
                }
            }

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function generateByCity(City $city): RedirectResponse
    {
        try {
            $city->loadMissing('districts');

            $districts = $city->districts;

            $formOfEducations = FormOfEducation::with('educationUnit')
                ->get();

            foreach ($districts as $district) {
                foreach ($formOfEducations as $formOfEducation) {
                    CreateSchoolJob::dispatch($district, $formOfEducation);
                }
            }
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

            DistrictLoopingJob::dispatch();

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }
}
