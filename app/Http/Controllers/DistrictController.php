<?php

namespace App\Http\Controllers;

use App\Jobs\CreateDistrictJob;
use App\Models\City;
use App\Models\District;
use App\Models\FormOfEducation;
use App\Models\Province;
use Exception;
use Illuminate\Http\Request;
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

    public function show(Request $request, District $district): View
    {
        $district->load([
            'city',
            'schools' => function ($query) use ($request) {
                $query->filterData($request);
            },
            'schools.formOfEducation',
            'schools.district.city.province'
        ]);
        $formOfEducation = FormOfEducation::get();

        return \view('district.show', compact('district', 'formOfEducation'));
    }
}
