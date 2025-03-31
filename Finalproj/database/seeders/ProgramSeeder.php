<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program; // Import the Program model
use Illuminate\Support\Facades\DB; // Optional: If you need DB facade

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table first to avoid duplicates if run multiple times
        // Program::truncate(); // Use with caution, disables foreign key checks temporarily if needed

        $programs = [
            ['name' => 'Bachelor of Science in Computer Science', 'code' => 'BSCS'],
            ['name' => 'Bachelor of Science in Information Technology', 'code' => 'BSIT'],
            ['name' => 'Bachelor of Science in Nursing', 'code' => 'BSN'],
            ['name' => 'Bachelor of Arts in Communication', 'code' => 'BACOMM'],
            ['name' => 'Bachelor of Science in Business Administration', 'code' => 'BSBA'],
        ];

        foreach ($programs as $program) {
            // Use firstOrCreate to avoid creating duplicates if the code already exists
            Program::firstOrCreate(
                ['code' => $program['code']], // Check based on code
                ['name' => $program['name']]   // Data to insert/update
            );
             $this->command->info("Seeded Program: {$program['code']} - {$program['name']}"); // Optional: Output info
        }
    }
}