<?php

namespace App\Http\Controllers;

use App\Exports\SchoolExport;
use App\Jobs\CreateSchoolJob;
use App\Models\City;
use App\Models\District;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public function store(City $city)
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

    public function export(Request $request, District $district)
    {
        try {
            return Excel::download(new SchoolExport($request, $district->code), 'schools.csv');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal diunduh');
        }
    }
}
