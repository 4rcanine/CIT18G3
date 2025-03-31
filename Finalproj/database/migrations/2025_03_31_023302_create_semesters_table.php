<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "1st Semester 2023-2024"
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('enrollment_start');
            $table->dateTime('enrollment_end');
            $table->boolean('is_active')->default(false); // Only one should be active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};