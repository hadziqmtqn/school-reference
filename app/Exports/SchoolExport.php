<?php

namespace App\Exports;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    use Exportable;

    protected Request $request;
    protected mixed $district;

    /**
     * @param Request $request
     * @param mixed $district
     */
    public function __construct(Request $request, mixed $district)
    {
        $this->request = $request;
        $this->district = $district;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return School::with('formOfEducation', 'district.city.province')
            ->filterData($this->request)
            ->whereHas('district', fn($query) => $query->where('code', $this->district))
            ->get()
            ->map(function (School $school) {
                static $no = 0;
                $no++;

                return collect([
                    $no,
                    $school->npsn,
                    $school->name,
                    strtoupper(optional($school->formOfEducation)->name),
                    $school->street,
                    strtoupper($school->village),
                    strtoupper(optional($school->district)->name),
                    strtoupper(optional(optional($school->district)->city)->name),
                    strtoupper(optional(optional(optional($school->district)->city)->province)->name),
                ]);
            });
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'no',
            'npsn',
            'name',
            'educational_group',
            'street',
            'village',
            'district',
            'city',
            'province'
        ];
    }

    public function getCsvSettings(): array
    {
        // TODO: Implement getCsvSettings() method.
        return [
            'delimiter' => ';'
        ];
    }
}
