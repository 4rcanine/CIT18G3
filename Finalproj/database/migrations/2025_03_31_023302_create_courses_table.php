<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., CS101
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('units', 3, 1)->unsigned();
            // If a course strictly belongs to one program:
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
            // If courses can belong to multiple programs, remove program_id
            // and create a program_course pivot table later.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};