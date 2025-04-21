<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Jobs\CreateCityJob;
use App\Models\City;
use App\Models\Province;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    use ApiResponse;

    public function index(CityRequest $request): JsonResponse
    {
        try {
            $cities = City::filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $cities->map(function (City $city) {
            return collect([
                'code' => $city->code,
                'name' => $city->name
            ]);
        }), Response::HTTP_OK);
    }

    public function store()
    {
        try {
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

    public function show(City $city): View
    {
        $city->load('districts');

        return \view('city.show', compact('city'));
    }
}
