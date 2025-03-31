<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->enum('year_level', ['freshman', 'sophomore', 'junior']); // Or use integer 1, 2, 3
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null'); // Link to programs
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_initial', 5)->nullable(); // Allow a bit more space
            $table->date('birthday');
            $table->string('sex'); // Consider enum: ['male', 'female', 'other'] if preferred
            $table->string('nationality');
            $table->text('address');
            $table->string('civil_status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};