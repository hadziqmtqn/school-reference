<?php

namespace App\Http\Requests\School;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'nullable'],
            'form-of-edu' => ['required', 'string', 'exists:form_of_education,slug'],
            'district_code' => ['required', 'exists:districts,code']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
