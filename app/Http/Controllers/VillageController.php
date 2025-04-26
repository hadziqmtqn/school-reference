<?php

namespace App\Http\Controllers;

use App\Http\Requests\VillageRequest;
use App\Models\School;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VillageController extends Controller
{
    use ApiResponse;

    public function index(VillageRequest $request): JsonResponse
    {
        try {
            $schools = School::select('village')
                ->distinct('village')
                ->filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $schools->map(function (School $school) {
            return collect([
                //'npsn' => $school->npsn,
                'name' => $school->village,
                /*'street' => $school->street,
                'village' => $school->village,
                'status' => $school->status,
                'formOfEducation' => optional($school->formOfEducation)->name*/
            ]);
        }), Response::HTTP_OK);
    }
}
