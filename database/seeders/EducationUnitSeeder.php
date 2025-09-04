<?php

namespace Database\Seeders;

use App\Models\EducationUnit;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class EducationUnitSeeder extends Seeder
{
    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function run(): void
    {
        $rows = Reader::createFromPath(database_path('import/education-unit.csv'))
            ->setHeaderOffset(0)
            ->setDelimiter(';');

        foreach ($rows as $row) {
            $educationUnit = new EducationUnit();
            $educationUnit->code = $row['code'];
            $educationUnit->name = $row['name'];
            $educationUnit->save();
        }
    }
}
