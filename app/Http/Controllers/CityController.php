<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Requests\School\CreateAllRequest;
use App\Models\City;
use App\Models\Province;
use App\Services\GenerateCityDataService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    use ApiResponse;

    protected GenerateCityDataService $generateCityDataService;

    /**
     * @param GenerateCityDataService $generateCityDataService
     */
    public function __construct(GenerateCityDataService $generateCityDataService)
    {
        $this->generateCityDataService = $generateCityDataService;
    }

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

    public function store(CreateAllRequest $request)
    {
        return $this->generateCityDataService->createByAll($request);
    }

    public function generateByProvince(Province $province)
    {
        return $this->generateCityDataService->createByProvince($province);
    }

    public function show(City $city): View
    {
        $city->load('districts');

        return \view('city.show', compact('city'));
    }
}
