<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class ProvinceSeeder extends Seeder
{
    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function run(): void
    {
        $rows = Reader::createFromPath(database_path('import/provinces.csv'))
            ->setHeaderOffset(0)
            ->setDelimiter(';');

        foreach ($rows as $row) {
            $province = new Province();
            $province->name = $row['name'];
            $province->code = $row['code'];
            $province->url = $row['url'];
            $province->save();
        }
    }
}
