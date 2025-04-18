<?php

namespace App\Http\Controllers;

use App\Jobs\CreateDistrictJob;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DistrictController extends Controller
{
    public function store(Province $province)
    {
        try {
            $cities = City::provinceId($province->id)
                ->get();

            foreach ($cities as $city) {
                CreateDistrictJob::dispatch($city);
            }

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan!');
        }
    }

    public function show(District $district): View
    {
        $district->load('city');

        return \view('district.show', compact('district'));
    }
}
