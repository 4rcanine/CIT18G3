<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Student
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->string('description'); // e.g., Tuition Fee - 1st Sem 23-24, Payment OR#123
            $table->decimal('debit', 10, 2)->default(0.00); // Amount charged
            $table->decimal('credit', 10, 2)->default(0.00); // Amount paid
            $table->timestamp('transaction_date')->useCurrent();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_ledgers');
    }
};