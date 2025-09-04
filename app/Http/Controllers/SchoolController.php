<?php

namespace App\Http\Controllers;

use App\Exports\SchoolExport;
use App\Http\Requests\School\CreateAllRequest;
use App\Http\Requests\School\ExportRequest;
use App\Http\Requests\School\SchoolRequest;
use App\Jobs\CreateSchoolJob;
use App\Models\City;
use App\Models\District;
use App\Models\FormOfEducation;
use App\Models\School;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    use ApiResponse;

    public function index(SchoolRequest $request): JsonResponse
    {
        try {
            $schools = School::with('formOfEducation:id,name')
                ->filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $schools->map(function (School $school) {
            return collect([
                'npsn' => $school->npsn,
                'name' => $school->name,
                'street' => $school->street,
                'village' => $school->village,
                'status' => $school->status,
                'formOfEducation' => $school->formOfEducation?->name
            ]);
        }), Response::HTTP_OK);
    }

    public function store(City $city)
    {
        try {
            $districts = District::whereHas('city', fn($query) => $query->filterByCode($city->code))
                ->get();

            $formOfEducations = FormOfEducation::with('educationUnit')
                ->get();

            foreach ($districts as $district) {
                foreach ($formOfEducations as $formOfEducation) {
                    CreateSchoolJob::dispatch($district, $formOfEducation);
                }
            }
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function createAllSchool(CreateAllRequest $request)
    {
        try {
            if ($request->input('token') !== 'HAbesar2') {
                return redirect()->back()->with('error', 'Token tidak valid');
            }

            $cities = City::with('districts')
                ->get();

            $formOfEducations = FormOfEducation::with('educationUnit')
                ->get();

            foreach ($cities as $city) {
                $districts = $city->districts;

                foreach ($districts as $district) {
                    foreach ($formOfEducations as $formOfEducation) {
                        CreateSchoolJob::dispatch($district, $formOfEducation);
                    }
                }
            }

            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function export(ExportRequest $request, District $district)
    {
        try {
            $district->load('city.province');
            $formOfEducation = $request->input('form-of-edu') != 'all' ? FormOfEducation::filterBySlug($request->input('form-of-edu'))
                ->first()
                ->name : null;
            $fileName = Str::slug('school-data-' . '-' . optional(optional($district->city)->province)->name . '-' . optional($district->city)->name . '-' . $district->name  . ($formOfEducation ? '-' . $formOfEducation : null));

            return Excel::download(new SchoolExport($request, $district->code),  $fileName . '.csv');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal diunduh');
        }
    }
}
