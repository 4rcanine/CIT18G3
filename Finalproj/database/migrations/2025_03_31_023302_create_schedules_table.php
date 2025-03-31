<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->string('instructor_name')->nullable(); // Can be FK later
            $table->string('section_code'); // e.g., BSCS-3A
            $table->string('schedule_info'); // e.g., "MWF 9:00-10:00 / Room 404"
            $table->unsignedInteger('slots')->nullable(); // Optional capacity
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};