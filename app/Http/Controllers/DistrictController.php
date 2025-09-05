<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictRequest;
use App\Http\Requests\School\CreateAllRequest;
use App\Models\City;
use App\Models\District;
use App\Models\FormOfEducation;
use App\Services\GenerateDistrictDataService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DistrictController extends Controller
{
    use ApiResponse;

    protected GenerateDistrictDataService $generateDistrictDataService;

    /**
     * @param GenerateDistrictDataService $generateDistrictDataService
     */
    public function __construct(GenerateDistrictDataService $generateDistrictDataService)
    {
        $this->generateDistrictDataService = $generateDistrictDataService;
    }

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

    public function store(CreateAllRequest $request): RedirectResponse
    {
        return $this->generateDistrictDataService->createByAll($request);
    }

    public function generateByCity(City $city): RedirectResponse
    {
        return $this->generateDistrictDataService->createByCity($city);
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
