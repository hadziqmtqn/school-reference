<?php

namespace App\Http\Controllers;

use App\Models\FormOfEducation;
use Illuminate\Http\Request;

class FormOfEducationController extends Controller
{
    public function index()
    {
        return FormOfEducation::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required'],
            'code' => ['required'],
            'name' => ['required'],
        ]);

        return FormOfEducation::create($data);
    }

    public function show(FormOfEducation $formOfEducation)
    {
        return $formOfEducation;
    }

    public function update(Request $request, FormOfEducation $formOfEducation)
    {
        $data = $request->validate([
            'slug' => ['required'],
            'code' => ['required'],
            'name' => ['required'],
        ]);

        $formOfEducation->update($data);

        return $formOfEducation;
    }

    public function destroy(FormOfEducation $formOfEducation)
    {
        $formOfEducation->delete();

        return response()->json();
    }
}
