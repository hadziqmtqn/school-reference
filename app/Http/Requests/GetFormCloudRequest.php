<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class GetFormCloudRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'education_unit_code' => ['required', 'string', 'exists:education_units,code'],
            'province_code' => ['nullable'],
            'city_code' => ['nullable'],
            'district_code' => ['nullable'],
            'village' => ['nullable'],
            'form-of-edu' => ['nullable', 'string', 'exists:form_of_education,slug'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
