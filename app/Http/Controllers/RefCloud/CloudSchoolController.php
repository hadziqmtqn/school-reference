<?php

namespace App\Http\Controllers\RefCloud;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetFormCloudRequest;
use App\Models\FormOfEducation;
use App\Traits\ApiCloud;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CloudSchoolController extends Controller
{
    use ApiCloud, ApiResponse;

    public function index(GetFormCloudRequest $request): JsonResponse
    {
        try {
            $educationUnitCode = $request->get('education_unit_code');

            $formOfEducation = FormOfEducation::query()
                ->whereHas('educationUnit', function ($query) use ($educationUnitCode) {
                    $query->where('code', $educationUnitCode);
                })
                ->filterBySlug($request->get('form-of-edu'))
                ->first();

            $endpoint = config('kemdikbud.source_endpoint') . '/' . $request->get('education_unit_code') . '/' . $request->get('district_code') . '/3/all/' . $formOfEducation?->code . '/all';

            return $this->apiResponse('Get data success', $this->getSchool($endpoint), Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal Server Error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
