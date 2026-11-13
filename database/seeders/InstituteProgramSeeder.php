<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institute;
use App\Models\Program;

class InstituteProgramSeeder extends Seeder
{
    public function run(): void
    {
        $institutes = [
            [
                'code' => 'IFMS',
                'name' => 'Institute of Fisheries and Marine Sciences',
                'programs' => [
                    ['code' => 'DFT', 'name' => 'Diploma in Fisheries Technology'],
                    ['code' => 'BSEnviSci', 'name' => 'Bachelor of Science in Environmental Science'],
                    ['code' => 'BSF', 'name' => 'Bachelor of Science in Fisheries'],
                    ['code' => 'BSMB', 'name' => 'Bachelor of Science in Marine Biology'],
                ],
            ],
            [
                'code' => 'IHS',
                'name' => 'Institute of Human Services',
                'programs' => [
                    ['code' => 'BSSW', 'name' => 'Bachelor of Science in Social Work'],
                    ['code' => 'BSCrim', 'name' => 'Bachelor of Science in Criminology'],
                ],
            ],
            [
                'code' => 'ITED',
                'name' => 'Institute of Teacher Education',
                'programs' => [
                    ['code' => 'BEED', 'name' => 'Bachelor in Elementary Education'],
                    ['code' => 'BSED-English', 'name' => 'Bachelor in Secondary Education major in English'],
                    ['code' => 'BSED-Math', 'name' => 'Bachelor in Secondary Education major in Mathematics'],
                    ['code' => 'BSED-Science', 'name' => 'Bachelor in Secondary Education major in Science'],
                    ['code' => 'BTLEd', 'name' => 'Bachelor of Technology Livelihood Education major in Home Economics'],
                ],
            ],
            [
                'code' => 'ICET',
                'name' => 'Institute of Computing, Engineering, and Technology',
                'programs' => [
                    ['code' => 'ACT', 'name' => 'Associate in Computer Technology'],
                    ['code' => 'BIndTech', 'name' => 'Bachelor in Industrial Technology major in Computer Technology'],
                    ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology'],
                    ['code' => 'BSABE', 'name' => 'Bachelor of Science in Agricultural and Biosystems Engineering'],
                ],
            ],
            [
                'code' => 'IASDC',
                'name' => 'Institute of Agricultural Sciences and Development Communication',
                'programs' => [
                    ['code' => 'BSA', 'name' => 'Bachelor of Science in Agriculture major in Horticulture'],
                    ['code' => 'BSDevCom', 'name' => 'Bachelor of Science in Development Communication'],
                ],
            ],
            [
                'code' => 'IBMG',
                'name' => 'Institute of Business Management and Governance',
                'programs' => [
                    ['code' => 'BPA', 'name' => 'Bachelor in Public Administration'],
                    ['code' => 'BSAB', 'name' => 'Bachelor of Science in Agribusiness'],
                    ['code' => 'BSOA', 'name' => 'Bachelor of Science in Office Administration'],
                    ['code' => 'BSAIS', 'name' => 'Bachelor of Science in Accounting Information System'],
                    ['code' => 'BSTM', 'name' => 'Bachelor of Science in Tourism Management'],
                ],
            ],
            [
                'code' => 'IPGS',
                'name' => 'Institute of Professional and Graduate Studies',
                'programs' => [],
            ],
        ];

        foreach ($institutes as $instituteData) {
            $institute = Institute::updateOrCreate(
                ['code' => $instituteData['code']],
                ['name' => $instituteData['name']]
            );

            foreach ($instituteData['programs'] as $programData) {
                Program::updateOrCreate(
                    ['code' => $programData['code']],
                    [
                        'institute_id' => $institute->id,
                        'name' => $programData['name'],
                    ]
                );
            }
        }

        $this->command?->info('SPAMAST institutes and undergraduate programs seeded successfully.');
    }
}
