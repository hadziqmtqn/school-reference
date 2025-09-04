<?php

namespace App\Http\Controllers\RefCloud;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetFormCloudRequest;
use App\Traits\ApiCloud;
use DiDom\Exceptions\InvalidSelectorException;

class CloudDistrictController extends Controller
{
    use ApiCloud;

    /**
     * @throws InvalidSelectorException
     */
    public function index(GetFormCloudRequest $request)
    {
        $educationUnitCode = $request->get('education_unit_code');
        $cityCode = $request->get('city_code');

        $endpoint = config('kemdikbud.source_endpoint') . '/' . $educationUnitCode . '/' . $cityCode;
        return $this->getData($endpoint, $educationUnitCode);
    }
}
