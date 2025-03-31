<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\Course;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schedule::truncate(); // Optional: Clear table

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
             $this->command->error("No active semester found. Cannot seed schedules.");
             return;
        }

        // Get some courses to create schedules for (adjust codes as needed)
        $courseCodes = ['CS101', 'CS102', 'CS201', 'CS202', 'CS301', 'IT101', 'IT102', 'IT201', 'IT202', 'NU101', 'NU102', 'ENGL1', 'MATH1'];
        $courses = Course::whereIn('code', $courseCodes)->get();

        if ($courses->isEmpty()) {
             $this->command->error("No courses found matching codes: " . implode(', ', $courseCodes) . ". Cannot seed schedules.");
             return;
        }

         $this->command->info("Seeding Schedules for Semester: {$activeSemester->name}...");

        $schedulesData = [
            // Course Code => [ [Section Data 1], [Section Data 2], ... ]
            'CS101' => [
                ['section' => 'A', 'time' => 'MWF 9:00-10:00 AM', 'room' => 'Rm 401', 'instructor' => 'Dr. Alice Smith'],
            ],
            'CS102' => [
                ['section' => 'A', 'time' => 'TTh 10:30-12:00 PM', 'room' => 'Rm 402', 'instructor' => 'Prof. Bob Jones'],
                ['section' => 'B', 'time' => 'MWF 1:00-2:00 PM', 'room' => 'Rm 403', 'instructor' => 'Prof. Bob Jones'],
            ],
            'CS201' => [
                ['section' => 'A', 'time' => 'TTh 1:00-2:30 PM', 'room' => 'Lab 1', 'instructor' => 'Dr. Carol White'],
            ],
             'CS202' => [
                ['section' => 'A', 'time' => 'MWF 10:00-11:00 AM', 'room' => 'Lab 2', 'instructor' => 'Dr. Alice Smith'],
            ],
             'CS301' => [ // Used by BSCS & BSIT
                ['section' => 'A', 'time' => 'TTh 9:00-10:30 AM', 'room' => 'Rm 501', 'instructor' => 'Prof. David Lee'],
                 ['section' => 'B', 'time' => 'MWF 2:00-3:00 PM', 'room' => 'Rm 502', 'instructor' => 'Prof. David Lee'],
            ],
            'IT101' => [
                 ['section' => 'A', 'time' => 'MWF 8:00-9:00 AM', 'room' => 'IT Rm 1', 'instructor' => 'Mr. Evan Green'],
            ],
            'IT102' => [
                 ['section' => 'A', 'time' => 'TTh 8:30-10:00 AM', 'room' => 'IT Lab A', 'instructor' => 'Ms. Fiona Black'],
            ],
             'IT201' => [
                 ['section' => 'A', 'time' => 'MWF 11:00-12:00 PM', 'room' => 'Net Lab', 'instructor' => 'Mr. Evan Green'],
            ],
             'IT202' => [
                 ['section' => 'A', 'time' => 'TTh 2:30-4:00 PM', 'room' => 'Web Lab', 'instructor' => 'Ms. Fiona Black'],
            ],
             'NU101' => [
                 ['section' => 'A', 'time' => 'MW 8:00-10:30 AM', 'room' => 'Nursing Hall A', 'instructor' => 'Dr. Grace Adams'],
             ],
             'NU102' => [
                 ['section' => 'A', 'time' => 'TTh 1:00-3:00 PM', 'room' => 'Nursing Hall B', 'instructor' => 'Prof. Henry Davis'],
             ],
             'ENGL1' => [
                 ['section' => 'A', 'time' => 'MWF 3:00-4:00 PM', 'room' => 'Gen Ed 101', 'instructor' => 'Ms. Ivy Stone'],
                 ['section' => 'B', 'time' => 'TTh 4:00-5:30 PM', 'room' => 'Gen Ed 102', 'instructor' => 'Ms. Ivy Stone'],
             ],
             'MATH1' => [
                  ['section' => 'A', 'time' => 'MWF 4:00-5:00 PM', 'room' => 'Sci Bldg 201', 'instructor' => 'Mr. Jack Miller'],
             ],

        ];

        foreach ($courses as $course) {
            if (isset($schedulesData[$course->code])) {
                foreach($schedulesData[$course->code] as $data) {
                    Schedule::updateOrCreate(
                        [ // Unique combination to check
                           'course_id' => $course->id,
                           'semester_id' => $activeSemester->id,
                           'section_code' => $course->program->code . '-' . $data['section'], // e.g., BSCS-A
                        ],
                        [ // Data to insert/update
                            'instructor_name' => $data['instructor'],
                            'schedule_info' => $data['time'] . ' / ' . $data['room'],
                            'slots' => 50, // Default slots, adjust as needed
                        ]
                    );
                     $this->command->info(" -- Scheduled: {$course->code} ({$course->program->code}-{$data['section']})");
                }
            }
        }
    }
}