<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\FormOfEducation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DistrictLoopingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $formOfEducations = FormOfEducation::with('educationUnit')->get();

        City::with('districts')->chunk(100, function ($cities) use ($formOfEducations) {
            foreach ($cities as $city) {
                foreach ($city->districts as $district) {
                    foreach ($formOfEducations as $formOfEducation) {
                        // Dispatch job untuk tiap district dan formOfEducation
                        CreateSchoolJob::dispatch($district, $formOfEducation);
                    }
                }
            }
        });
    }
}
