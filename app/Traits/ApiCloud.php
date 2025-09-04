<?php

namespace App\Traits;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait ApiCloud
{
    /**
     * @throws InvalidSelectorException
     */
    public function getData($endpoint, $educationUnitCode): ?array
    {
        try {
            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; MyScraper/1.0)'
                ]
            ]);
            $response = $client->get($endpoint);
            $html = (string) $response->getBody();
            $document = new Document($html);

            $rows = $document->find('table tr');

            $data = [];
            foreach ($rows as $tr) {
                $cols = $tr->find('td');

                if (count($cols) >= 2) {
                    $link = $cols[1]->first('a');
                    $name = $link ? trim($link->text()) : trim($cols[1]->text());
                    $href = $link?->getAttribute('href');
                    $code = null;
                    // Gunakan regex dinamis sesuai education_unit_code
                    if ($href && preg_match('#' . preg_quote($educationUnitCode, '#') . '/(\d+)/#', $href, $matches)) {
                        $code = $matches[1];
                    }

                    $data[] = [
                        'name' => $name,
                        'code' => $code,
                        //'href' => $href,
                    ];
                }
            }
            return $data;
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * @throws InvalidSelectorException
     */
    public function getSchool($endpoint): ?array
    {
        try {
            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; MyScraper/1.0)'
                ]
            ]);
            $response = $client->get($endpoint);
            $html = (string) $response->getBody();
            $document = new Document($html);

            $rows = $document->find('table tr');

            $data = [];
            foreach ($rows as $tr) {
                $cols = $tr->find('td');

                if (count($cols) >= 2) {
                    $link = $cols[1]->first('a');
                    $npsn = $link ? trim($link->text()) : trim($cols[1]->text());
                    $name = trim($cols[2]->text());
                    $street = trim($cols[3]->text());
                    $village = trim($cols[4]->text());
                    $status = trim($cols[5]->text());

                    $data[] = [
                        'npsn' => $npsn,
                        'name' => $name,
                        'street' => $street,
                        'village' => $village,
                        'status' => $status,
                    ];
                }
            }
            return $data;
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}