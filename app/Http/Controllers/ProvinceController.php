<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvinceRequest;
use App\Models\Province;
use App\Traits\ApiResponse;
use DiDom\Document;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProvinceController extends Controller
{
    use ApiResponse;

    public function index(ProvinceRequest $request): JsonResponse
    {
        try {
            $provinces = Province::filterData($request)
                ->get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Get data success', $provinces->map(function (Province $province) {
            return collect([
                'code' => $province->code,
                'name' => $province->name
            ]);
        }), Response::HTTP_OK);
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     */
    public function store()
    {
        try {
            $client = new Client();
            $response = $client->get(config('kemdikbud.source_endpoint') . '/dikdas');

            $html = (string) $response->getBody();
            $document = new Document($html);

            DB::beginTransaction();
            foreach ($document->find('table tr') as $tr) {
                $cols = $tr->find('td');

                if (count($cols) >= 2) {
                    $link = $cols[1]->first('a');
                    $name = $link ? trim($link->text()) : trim($cols[1]->text());
                    $href = $link?->getAttribute('href');

                    // Ambil kode provinsi dari href (contoh: dikdas/020000/1)
                    $code = null;
                    if ($href) {
                        if (preg_match('#dikdas/(\d+)/#', $href, $matches)) {
                            $code = $matches[1]; // hasil: 020000
                        }
                    }

                    $province = Province::filterByCode($code)
                        ->firstOrNew();
                    $province->name = $name;
                    $province->code = $code;
                    $province->url = $href;
                    $province->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function show(Province $province): View
    {
        $province->load('cities');
        return \view('province.show', compact('province'));
    }
}
