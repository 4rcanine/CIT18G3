<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Course;
use Illuminate\Support\Facades\DB; // Required for pivot table operations

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Course::truncate(); // Optional: Clear courses
        // DB::table('course_prerequisite')->truncate(); // Optional: Clear prerequisites

        // Fetch Programs first
        $programs = Program::pluck('id', 'code')->all(); // Get [ 'BSCS' => 1, 'BSIT' => 2, ... ]

        if (empty($programs)) {
             $this->command->error("No programs found. Please run ProgramSeeder first.");
             return;
        }

        $coursesData = [
            // BSCS Courses
            ['code' => 'CS101', 'name' => 'Introduction to Computing', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => []],
            ['code' => 'CS102', 'name' => 'Fundamentals of Programming', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => ['CS101']],
            ['code' => 'CS201', 'name' => 'Data Structures and Algorithms', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => ['CS102']],
            ['code' => 'CS202', 'name' => 'Object Oriented Programming', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => ['CS102']],
            ['code' => 'CS301', 'name' => 'Database Management Systems', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => ['CS201']],

            // BSIT Courses (some overlap, some specific)
            ['code' => 'IT101', 'name' => 'Introduction to IT', 'units' => 3.0, 'program_code' => 'BSIT', 'prerequisites' => []],
            ['code' => 'IT102', 'name' => 'Basic Programming', 'units' => 3.0, 'program_code' => 'BSIT', 'prerequisites' => ['IT101']],
            ['code' => 'IT201', 'name' => 'Networking Fundamentals', 'units' => 3.0, 'program_code' => 'BSIT', 'prerequisites' => ['IT101']],
            ['code' => 'IT202', 'name' => 'Web Development Basics', 'units' => 3.0, 'program_code' => 'BSIT', 'prerequisites' => ['IT102']],
            // Re-using CS301 for IT as well
            ['code' => 'CS301', 'name' => 'Database Management Systems', 'units' => 3.0, 'program_code' => 'BSIT', 'prerequisites' => ['IT102']],

            // BSN Courses
            ['code' => 'NU101', 'name' => 'Anatomy and Physiology', 'units' => 5.0, 'program_code' => 'BSN', 'prerequisites' => []],
            ['code' => 'NU102', 'name' => 'Fundamentals of Nursing', 'units' => 4.0, 'program_code' => 'BSN', 'prerequisites' => ['NU101']],

            // Generic Courses (Can be added to multiple programs if needed - adjust program_code or use pivot table later)
            ['code' => 'ENGL1', 'name' => 'English Communication Skills', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => []], // Assign to one for now
            ['code' => 'MATH1', 'name' => 'College Algebra', 'units' => 3.0, 'program_code' => 'BSCS', 'prerequisites' => []], // Assign to one for now
        ];

        $createdCourses = []; // To store created course IDs mapped by code

        // --- Pass 1: Create Courses ---
        $this->command->info("Seeding Courses...");
        foreach ($coursesData as $courseData) {
            if (!isset($programs[$courseData['program_code']])) {
                 $this->command->warn("Skipping course {$courseData['code']}: Program code {$courseData['program_code']} not found.");
                 continue;
            }

            $course = Course::updateOrCreate(
                [
                    'code' => $courseData['code'],
                    // If a course code can exist in multiple programs, add program_id here
                    // 'program_id' => $programs[$courseData['program_code']],
                ],
                [
                    'name' => $courseData['name'],
                    'units' => $courseData['units'],
                    'program_id' => $programs[$courseData['program_code']],
                    // Add description if available in $courseData
                ]
            );
            $createdCourses[$course->code] = $course->id; // Store ID by code
            $this->command->info(" -- {$courseData['code']} ({$courseData['program_code']})");
        }

        // --- Pass 2: Assign Prerequisites ---
         $this->command->info("Assigning Prerequisites...");
        foreach ($coursesData as $courseData) {
            if (empty($courseData['prerequisites'])) {
                continue; // Skip if no prerequisites defined
            }

            if (!isset($createdCourses[$courseData['code']])) {
                 $this->command->warn("Skipping prerequisites for {$courseData['code']}: Course not found (maybe skipped in Pass 1).");
                continue; // Course itself wasn't created
            }

            $courseModel = Course::find($createdCourses[$courseData['code']]);
            $prerequisiteIds = [];

            foreach ($courseData['prerequisites'] as $prerequisiteCode) {
                if (isset($createdCourses[$prerequisiteCode])) {
                    $prerequisiteIds[] = $createdCourses[$prerequisiteCode];
                } else {
                     $this->command->warn(" -- Prerequisite code '{$prerequisiteCode}' not found for course '{$courseData['code']}'. Skipping this prerequisite.");
                }
            }

            if (!empty($prerequisiteIds)) {
                // Sync prerequisites - detaches any not listed, attaches new ones
                $courseModel->prerequisites()->sync($prerequisiteIds);
                 $this->command->info(" -- Synced prerequisites for {$courseData['code']}");
            }
        }
    }
}