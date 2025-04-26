<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class VillageRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'nullable'],
            'district_code' => ['required', 'exists:districts,code']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
