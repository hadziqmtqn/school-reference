<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSchoolJob;
use App\Models\City;
use App\Models\District;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    public function store(Request $request, City $city)
    {
        try {
            $districts = District::whereHas('city', fn($query) => $query->filterByCode($city->code))
                ->get();

            foreach ($districts as $district) {
                CreateSchoolJob::dispatch($district);
            }
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function storeAll()
    {
        try {
            $districts = District::get();

            foreach ($districts as $district) {
                CreateSchoolJob::dispatch($district);
            }
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function show(School $school)
    {
        return $school;
    }
}
