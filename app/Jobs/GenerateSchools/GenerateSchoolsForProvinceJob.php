<?php

namespace App\Jobs\GenerateSchools;

use App\Models\Province;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSchoolsForProvinceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $provinceId;

    /**
     * @param mixed|null $provinceId
     */
    public function __construct(mixed $provinceId = null)
    {
        $this->provinceId = $provinceId;
    }

    public function handle(): void
    {
        $provinceQuery = Province::query();
        if ($this->provinceId) {
            $provinceQuery->where('id', $this->provinceId);
        }

        $provinceQuery->chunk(50, function ($provinces) {
            foreach ($provinces as $province) {
                GenerateSchoolsForCityJob::dispatch($province->id);
            }
        });
    }
}
