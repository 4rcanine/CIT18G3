<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Student
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade'); // Specific class section
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->enum('status', ['selected', 'locked', 'enrolled', 'completed', 'dropped'])->default('selected');
            $table->decimal('final_grade', 5, 2)->nullable(); // e.g., 1.75, 3.00
            $table->timestamps();

            // Prevent student from enrolling in the same section twice in a semester
            $table->unique(['user_id', 'schedule_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};