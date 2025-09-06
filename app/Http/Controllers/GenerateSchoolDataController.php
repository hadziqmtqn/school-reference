<?php

namespace App\Http\Controllers;

use App\Http\Requests\School\CreateAllRequest;
use App\Models\City;
use App\Models\Province;
use App\Services\GenerateSchoolDataService;

class GenerateSchoolDataController extends Controller
{
    protected GenerateSchoolDataService $generateSchoolDataService;

    /**
     * @param GenerateSchoolDataService $generateSchoolDataService
     */
    public function __construct(GenerateSchoolDataService $generateSchoolDataService)
    {
        $this->generateSchoolDataService = $generateSchoolDataService;
    }

    public function generateByProvince(CreateAllRequest $request, Province $province)
    {
        return $this->generateSchoolDataService->generateByProvince($request, $province);
    }

    public function generateByCity(CreateAllRequest $request, City $city)
    {
        return $this->generateSchoolDataService->generateByCity($request, $city);
    }

    public function createAllSchool(CreateAllRequest $request)
    {
        return $this->generateSchoolDataService->createAllSchool($request);
    }
}
