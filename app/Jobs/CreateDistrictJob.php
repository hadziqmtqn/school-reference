<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\District;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDistrictJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected City $city;

    public function __construct(City $city)
    {
        $this->city = $city;
    }

    /**
     * @throws GuzzleException
     * @throws InvalidSelectorException
     */
    public function handle(): void
    {
        $client = new Client();
        $response = $client->get(config('SourceEndpoint.source_endpoint') . $this->city->code);

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

                $city = District::filterByCode($code)
                    ->firstOrNew();
                $city->name = $name;
                $city->code = $code;
                $city->city_id = $this->city->id;
                $city->url = $href;
                $city->save();
            }
        }
    }
}
