<?php

namespace App\Jobs\GenerateSchools;

use App\Models\City;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSchoolsForCityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $provinceId;
    protected mixed $cityId;

    /**
     * @param $provinceId
     * @param $cityId
     */
    public function __construct($provinceId = null, $cityId = null)
    {
        $this->provinceId = $provinceId;
        $this->cityId = $cityId;
    }

    public function handle(): void
    {
        $cityQuery = City::query();
        if ($this->cityId) {
            $cityQuery->where('id', $this->cityId);
        } elseif ($this->provinceId) {
            $cityQuery->where('province_id', $this->provinceId);
        }

        $cityQuery->chunk(100, function ($cities) {
            foreach ($cities as $city) {
                GenerateSchoolsForDistrictJob::dispatch($city->id);
            }
        });
    }
}
