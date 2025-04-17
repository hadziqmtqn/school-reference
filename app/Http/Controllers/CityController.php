<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCityJob;
use App\Models\City;
use App\Models\Province;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function index()
    {
        return City::all();
    }

    public function store(Request $request)
    {
        try {
            $provinces = Province::get();

            foreach ($provinces as $province) {
                CreateCityJob::dispatch($province);
            }
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function show(City $city)
    {
        return $city;
    }

    public function update(Request $request, City $city)
    {
        $data = $request->validate([
            'slug' => ['required'],
            'code' => ['required'],
            'name' => ['required'],
            'province_code' => ['required'],
            'url' => ['nullable'],
        ]);

        $city->update($data);

        return $city;
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response()->json();
    }
}
