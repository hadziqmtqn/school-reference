<?php

namespace App\Console\Commands;

use App\Jobs\CreateDistrictJob;
use App\Models\City;
use Illuminate\Console\Command;

class CreateDistrictCommand extends Command
{
    protected $signature = 'create:district';

    protected $description = 'Command description';

    public function handle(): void
    {
        $cities = City::all();

        foreach ($cities as $city) {
            CreateDistrictJob::dispatch($city);
        }
    }
}
