<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'code' => ['required'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
