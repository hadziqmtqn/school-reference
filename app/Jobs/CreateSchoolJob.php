<?php

namespace App\Jobs;

use App\Models\District;
use App\Models\FormOfEducation;
use App\Models\School;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSchoolJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected District $district;
    protected FormOfEducation $formOfEducation;

    /**
     * @param District $district
     * @param FormOfEducation $formOfEducation
     */
    public function __construct(District $district, FormOfEducation $formOfEducation)
    {
        $this->district = $district;
        $this->formOfEducation = $formOfEducation;
    }

    /**
     * @throws InvalidSelectorException
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $client = new Client();
        $response = $client->get(config('kemdikbud.source_endpoint') . '/' . $this->formOfEducation->educationUnit?->code . '/' . $this->district->code . '/3/all/' . $this->formOfEducation->code . '/all');

        $html = (string) $response->getBody();
        $document = new Document($html);

        foreach ($document->find('table tr') as $tr) {
            $cols = $tr->find('td');

            if (count($cols) >= 2) {
                $link = $cols[1]->first('a');
                $npsn = $link ? trim($link->text()) : trim($cols[1]->text());
                $name = trim($cols[2]->text());
                $street = trim($cols[3]->text());
                $village = trim($cols[4]->text());
                $status = trim($cols[5]->text());

                $school = School::filterByNpsn($npsn)
                    ->firstOrNew();
                $school->npsn = $npsn;
                $school->name = $name;
                $school->district_id = $this->district->id;
                $school->form_of_education_id = $this->formOfEducation->id;
                $school->street = $street;
                $school->village = $village;
                $school->status = $status;
                $school->save();
            }
        }
    }
}
