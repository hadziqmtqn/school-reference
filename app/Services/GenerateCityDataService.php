<?php

namespace App\Services;

use App\Http\Requests\School\CreateAllRequest;
use App\Jobs\CreateCityJob;
use App\Models\Province;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class GenerateCityDataService
{
    public function createByProvince(Province $province): RedirectResponse
    {
        try {
            CreateCityJob::dispatch($province);
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
}
