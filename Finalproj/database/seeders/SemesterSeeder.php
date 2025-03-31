<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester; // Import Semester model
use Carbon\Carbon; // Import Carbon for dates

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Semester::truncate(); // Optional: Clear table first

        // --- Active Semester ---
        // Make sure enrollment_start/end covers the current date for testing
        $now = Carbon::now();
        $activeSemStart = $now->copy()->subMonth(); // Example: started last month
        $activeSemEnd = $now->copy()->addMonths(4); // Example: ends in 4 months
        $enrollStart = $now->copy()->subWeeks(2); // Example: enrollment opened 2 weeks ago
        $enrollEnd = $now->copy()->addWeeks(2);   // Example: enrollment closes in 2 weeks

        Semester::updateOrCreate(
            ['name' => '1st Semester 2024-2025'], // Unique identifier
            [
                'start_date' => $activeSemStart->toDateString(),
                'end_date' => $activeSemEnd->toDateString(),
                'enrollment_start' => $enrollStart->toDateTimeString(),
                'enrollment_end' => $enrollEnd->toDateTimeString(),
                'is_active' => true,
            ]
        );
         $this->command->info("Seeded Active Semester: 1st Semester 2024-2025");

        // --- Inactive Past Semester ---
        Semester::updateOrCreate(
             ['name' => '2nd Semester 2023-2024'],
             [
                'start_date' => '2024-01-15',
                'end_date' => '2024-05-30',
                'enrollment_start' => '2024-01-01 08:00:00',
                'enrollment_end' => '2024-01-14 17:00:00',
                'is_active' => false,
            ]
        );
        $this->command->info("Seeded Past Semester: 2nd Semester 2023-2024");

         // --- Inactive Future Semester ---
        Semester::updateOrCreate(
             ['name' => '2nd Semester 2024-2025'],
             [
                'start_date' => $activeSemEnd->copy()->addMonth()->startOfMonth()->toDateString(), // Start month after active ends
                'end_date' => $activeSemEnd->copy()->addMonths(5)->endOfMonth()->toDateString(), // End ~4 months later
                'enrollment_start' => $activeSemEnd->copy()->subWeek()->startOfDay()->toDateTimeString(), // Enroll ~1 week before it starts
                'enrollment_end' => $activeSemEnd->copy()->addMonth()->startOfMonth()->addWeek()->endOfDay()->toDateTimeString(), // Enroll for ~1 week after start
                'is_active' => false,
            ]
        );
         $this->command->info("Seeded Future Semester: 2nd Semester 2024-2025");
    }
}