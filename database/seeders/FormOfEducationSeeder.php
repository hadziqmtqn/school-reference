<?php

namespace Database\Seeders;

use App\Models\FormOfEducation;
use Illuminate\Database\Seeder;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class FormOfEducationSeeder extends Seeder
{
    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function run(): void
    {
        $rows = Reader::createFromPath(database_path('import/form-of-education.csv'))
            ->setHeaderOffset(0);

        foreach ($rows as $row) {
            $formOfEducation = new FormOfEducation();
            $formOfEducation->code = $row['code'];
            $formOfEducation->name = $row['name'];
            $formOfEducation->save();
        }
    }
}
