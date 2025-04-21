<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictRequest;
use App\Jobs\CreateDistrictJob;
use App\Models\City;
use App\Models\District;
use App\Models\FormOfEducation;
use App\Models\Province;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DistrictController extends Controller
{
    use ApiResponse;

    public function index(DistrictRequest $request): JsonResponse
    {
        try {
            $districts = District::filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $districts->map(function (District $district) {
            return collect([
                'code' => $district->code,
                'name' => $district->name
            ]);
        }), Response::HTTP_OK);
    }

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
