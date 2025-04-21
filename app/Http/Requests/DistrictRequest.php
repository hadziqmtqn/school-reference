<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:3'],
            'city_code' => ['required', 'exists:cities,code']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
