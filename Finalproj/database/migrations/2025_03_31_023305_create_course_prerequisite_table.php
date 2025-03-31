<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_prerequisite', function (Blueprint $table) {
            // The course that HAS the prerequisite
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            // The course that IS the prerequisite
            $table->foreignId('prerequisite_course_id')->constrained('courses')->onDelete('cascade');

            // Prevent duplicate entries
            $table->primary(['course_id', 'prerequisite_course_id']);

            // No timestamps needed unless you want to track when prerequisites were set
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_prerequisite');
    }
};