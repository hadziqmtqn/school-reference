<?php

namespace App\Jobs\GenerateLocation;

use App\Models\City;
use App\Models\Province;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Province $province;

    public function __construct(Province $province)
    {
        $this->province = $province;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidSelectorException
     */
    public function handle(): void
    {
        $client = new Client();
        $response = $client->get(config('kemdikbud.source_endpoint') . '/dikdas/' . $this->province->code);

        $html = (string) $response->getBody();
        $document = new Document($html);

        foreach ($document->find('table tr') as $tr) {
            $cols = $tr->find('td');

            if (count($cols) >= 2) {
                $link = $cols[1]->first('a');
                $name = $link ? trim($link->text()) : trim($cols[1]->text());
                $href = $link?->getAttribute('href');

                $code = null;
                if ($href && preg_match('#dikdas/(\d+)/#', $href, $matches)) {
                    $code = $matches[1];
                }

                $city = City::filterByCode($code)
                    ->lockForUpdate()
                    ->firstOrNew();
                $city->name = $name;
                $city->code = $code;
                $city->province_id = $this->province->id;
                $city->url = $href;
                $city->save();
            }
        }
    }
}
