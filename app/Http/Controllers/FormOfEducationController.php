<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormOfEducationRequest;
use App\Models\FormOfEducation;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormOfEducationController extends Controller
{
    use ApiResponse;

    public function index(FormOfEducationRequest $request): JsonResponse
    {
        try {
            $formOfEducations = FormOfEducation::query()
                ->with('educationUnit:id,code,name')
                ->filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $formOfEducations->map(function (FormOfEducation $formOfEducation) {
            return collect([
                'slug' => $formOfEducation->slug,
                'name' => $formOfEducation->name,
                'educationUnit' => $formOfEducation->educationUnit?->code,
            ]);
        }), Response::HTTP_OK);
    }
}
