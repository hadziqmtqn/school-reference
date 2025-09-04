<?php

namespace App\Http\Controllers\RefCloud;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetFormCloudRequest;
use App\Traits\ApiCloud;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CloudCityController extends Controller
{
    use ApiCloud, ApiResponse;

    public function index(GetFormCloudRequest $request): JsonResponse
    {
        try {
            $educationUnitCode = $request->get('education_unit_code');
            $provinceCode = $request->get('province_code');

            $endpoint = config('kemdikbud.source_endpoint') . '/' . $educationUnitCode . '/' . $provinceCode;

            return $this->apiResponse('Get data success', $this->getData($endpoint, $educationUnitCode), Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal Server Error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
