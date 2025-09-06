<?php

namespace App\Jobs\GenerateLocation;

use App\Models\City;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDistrictsForCitiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $cities = City::query();

        $cities->chunk(100, function ($cities) {
            foreach ($cities as $city) {
                CreateDistrictJob::dispatch($city);
            }
        });
    }
}
