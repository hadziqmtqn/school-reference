<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:3'],
            'province_code' => ['required', 'exists:provinces,code']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
