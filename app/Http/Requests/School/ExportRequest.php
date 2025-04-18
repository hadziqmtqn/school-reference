<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:3'],
            'form-of-edu' => ['nullable', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
