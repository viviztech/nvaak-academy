<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Institute;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $institute = Institute::where('slug', 'nvaak-academy')->first();

        if (!$institute) {
            $this->command->warn('Institute not found. Run InstituteSeeder first.');
            return;
        }

        // NEET Subjects
        $neetSubjects = [
            ['name' => 'Physics',          'code' => 'PHY',  'color_code' => '#3B82F6', 'course_type' => 'neet', 'display_order' => 1],
            ['name' => 'Chemistry',        'code' => 'CHEM', 'color_code' => '#10B981', 'course_type' => 'neet', 'display_order' => 2],
            ['name' => 'Biology-Botany',   'code' => 'BOT',  'color_code' => '#84CC16', 'course_type' => 'neet', 'display_order' => 3],
            ['name' => 'Biology-Zoology',  'code' => 'ZOO',  'color_code' => '#F59E0B', 'course_type' => 'neet', 'display_order' => 4],
        ];

        // IAS Subjects
        $iasSubjects = [
            ['name' => 'General Studies I',   'code' => 'GS1', 'color_code' => '#6366F1', 'course_type' => 'ias', 'display_order' => 1],
            ['name' => 'General Studies II',  'code' => 'GS2', 'color_code' => '#8B5CF6', 'course_type' => 'ias', 'display_order' => 2],
            ['name' => 'General Studies III', 'code' => 'GS3', 'color_code' => '#EC4899', 'course_type' => 'ias', 'display_order' => 3],
            ['name' => 'General Studies IV',  'code' => 'GS4', 'color_code' => '#F43F5E', 'course_type' => 'ias', 'display_order' => 4],
            ['name' => 'Essay',               'code' => 'ESS', 'color_code' => '#14B8A6', 'course_type' => 'ias', 'display_order' => 5],
            ['name' => 'Optional Subject',    'code' => 'OPT', 'color_code' => '#F97316', 'course_type' => 'ias', 'display_order' => 6],
        ];

        $allSubjects = array_merge($neetSubjects, $iasSubjects);

        $physicsSubject = null;

        foreach ($allSubjects as $subjectData) {
            $subject = Subject::firstOrCreate(
                [
                    'institute_id' => $institute->id,
                    'code'         => $subjectData['code'],
                ],
                [
                    'name'          => $subjectData['name'],
                    'course_type'   => $subjectData['course_type'],
                    'color_code'    => $subjectData['color_code'],
                    'display_order' => $subjectData['display_order'],
                    'is_active'     => true,
                ]
            );

            if ($subjectData['code'] === 'PHY') {
                $physicsSubject = $subject;
            }
        }

        // Seed Physics Chapters
        if ($physicsSubject) {
            $physicsChapters = [
                'Units and Measurements',
                'Motion in a Straight Line',
                'Motion in a Plane',
                'Laws of Motion',
                'Work Energy and Power',
                'System of Particles',
                'Gravitation',
                'Mechanical Properties of Solids',
                'Mechanical Properties of Fluids',
                'Thermal Properties of Matter',
                'Thermodynamics',
                'Kinetic Theory',
                'Oscillations',
                'Waves',
                'Electric Charges and Fields',
                'Electrostatic Potential',
                'Current Electricity',
                'Moving Charges and Magnetism',
                'Magnetism and Matter',
                'Electromagnetic Induction',
                'Alternating Current',
                'Electromagnetic Waves',
                'Ray Optics',
                'Wave Optics',
                'Dual Nature of Radiation',
                'Atoms',
                'Nuclei',
                'Semiconductor Devices',
            ];

            foreach ($physicsChapters as $index => $chapterName) {
                Chapter::firstOrCreate(
                    [
                        'subject_id' => $physicsSubject->id,
                        'name'       => $chapterName,
                    ],
                    [
                        'display_order'          => $index + 1,
                        'neet_weightage_percent' => round(100 / count($physicsChapters), 2),
                        'ias_weightage_percent'  => 0,
                        'estimated_hours'        => 6,
                        'is_active'              => true,
                    ]
                );
            }

            $this->command->info('Physics chapters seeded: ' . count($physicsChapters) . ' chapters.');
        }

        $this->command->info('Subjects seeded successfully.');
    }
}
