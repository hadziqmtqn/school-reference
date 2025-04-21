<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use App\Traits\HandlesValidationFailure;
use Illuminate\Foundation\Http\FormRequest;

class FormOfEducationRequest extends FormRequest
{
    use ApiResponse, HandlesValidationFailure;

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
