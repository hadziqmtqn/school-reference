<?php

namespace App\Http\Controllers;

use App\Models\School;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * @throws GuzzleException
     * @throws InvalidSelectorException
     */
    public function index()
    {
        $client = new Client();
        $response = $client->get(config('SourceEndpoint.source_endpoint') . '/030101/3/all/5/all');

        $html = (string) $response->getBody();
        $document = new Document($html);

        $data = [];
        foreach ($document->find('table tr') as $tr) {
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
                    'status' => $status
                ];
            }
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required'],
            'name' => ['required'],
            'npsn' => ['nullable'],
            'street' => ['nullable'],
            'village' => ['nullable'],
            'status' => ['required'],
        ]);

        return School::create($data);
    }

    public function show(School $school)
    {
        return $school;
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'slug' => ['required'],
            'name' => ['required'],
            'npsn' => ['nullable'],
            'street' => ['nullable'],
            'village' => ['nullable'],
            'status' => ['required'],
        ]);

        $school->update($data);

        return $school;
    }

    public function destroy(School $school)
    {
        $school->delete();

        return response()->json();
    }
}
