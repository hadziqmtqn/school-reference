<?php

namespace App\Jobs\GenerateSchools;

use App\Models\District;
use App\Models\FormOfEducation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSchoolsForDistrictJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $cityId;
    protected mixed $districtId;

    /**
     * @param $cityId
     * @param $districtId
     */
    public function __construct($cityId = null, $districtId = null)
    {
        $this->cityId = $cityId;
        $this->districtId = $districtId;
    }

    public function handle(): void
    {
        $districtQuery = District::query();
        if ($this->districtId) {
            $districtQuery->where('id', $this->districtId);
        } elseif ($this->cityId) {
            $districtQuery->where('city_id', $this->cityId);
        }

        $formOfEducations = FormOfEducation::with('educationUnit')
            ->get();

        $districtQuery->chunk(100, function ($districts) use ($formOfEducations) {
            foreach ($districts as $district) {
                foreach ($formOfEducations as $formOfEducation) {
                    CreateSchoolJob::dispatch($district, $formOfEducation);
                }
            }
        });
    }
}
