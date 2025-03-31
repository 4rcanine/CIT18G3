<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Keep or remove default user factory

        // Call your custom seeders in order of dependency
        $this->call([
            ProgramSeeder::class,
            SemesterSeeder::class,
            CourseSeeder::class, // This now handles courses AND prerequisites
            ScheduleSeeder::class,
            // Add other seeders like UserSeeder if you create one
        ]);

         $this->command->info('All custom seeders executed successfully!'); // Optional final message
    }
}