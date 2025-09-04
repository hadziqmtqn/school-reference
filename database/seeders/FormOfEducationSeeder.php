<?php

namespace Database\Seeders;

use App\Models\EducationUnit;
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
            $educationUnit = EducationUnit::filterByCode($row['education_unit'])
                ->firstOrFail();

            $formOfEducation = new FormOfEducation();
            $formOfEducation->code = $row['code'];
            $formOfEducation->name = $row['name'];
            $formOfEducation->education_unit_id = $educationUnit->id;
            $formOfEducation->save();
        }
    }
}
