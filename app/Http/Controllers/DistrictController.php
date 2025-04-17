<?php

namespace App\Http\Controllers;

use App\Jobs\CreateDistrictJob;
use App\Models\City;
use App\Models\District;
use Exception;
use Illuminate\Support\Facades\Log;

class DistrictController extends Controller
{
    public function store()
    {
        try {
            $cities = City::get();

            foreach ($cities as $city) {
                CreateDistrictJob::dispatch($city);
            }

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }
    }

    public function show(District $district)
    {
        return $district;
    }
}
