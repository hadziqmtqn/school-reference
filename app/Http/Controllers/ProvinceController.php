<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvinceRequest;
use App\Models\Province;
use DiDom\Document;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProvinceController extends Controller
{
    public function index(): View
    {
        $provinces = Province::get();

        return \view('province', compact('provinces'));
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     */
    public function store()
    {
        try {
            $client = new Client();
            $response = $client->get(config('SourceEndpoint.source_endpoint'));

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

                    /*$data[] = [
                        'name' => $provinsi,
                        'link' => $href,
                        'code' => $code,
                    ];*/
                    $province = Province::filterByCode($code)
                        ->firstOrNew();
                    $province->name = $name;
                    $province->code = $code;
                    $province->url = $href;
                    $province->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diproses');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Data gagal disimpan');
        }
    }

    public function show(Province $province)
    {
        return $province;
    }

    public function update(ProvinceRequest $request, Province $province)
    {
        $province->update($request->validated());

        return $province;
    }

    public function destroy(Province $province)
    {
        $province->delete();

        return response()->json();
    }
}
