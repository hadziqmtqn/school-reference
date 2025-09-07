<?php

namespace App\Jobs\GenerateSchools;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveSchoolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $school = new School();
        $school->npsn = $this->data['npsn'];
        $school->name = $this->data['name'];
        $school->district_id = $this->data['district'];
        $school->form_of_education_id = $this->data['formOfEducation'];
        $school->street = $this->data['street'];
        $school->village = $this->data['village'];
        $school->status = $this->data['status'];
        $school->save();
    }
}
