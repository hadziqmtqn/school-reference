<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class CitySeeder extends Seeder
{
    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function run(): void
    {
        $rows = Reader::createFromPath(database_path('import/cities.csv'))
            ->setHeaderOffset(0)
            ->setDelimiter(';');

        foreach ($rows as $row) {
            $city = new City();
            $city->name = $row['name'];
            $city->code = $row['code'];
            $city->province_id = $row['province_id'];
            $city->url = $row['url'];
            $city->save();
        }
    }
}
