<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'enrollment_start',
        'enrollment_end',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_start' => 'datetime',
        'enrollment_end' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationship to Schedules offered in this semester
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

     // Relationship to Enrollments made in this semester
     public function enrollments()
     {
         return $this->hasMany(Enrollment::class);
     }

     // Relationship to Payments made for this semester
     public function paymentLedgerEntries()
     {
         return $this->hasMany(PaymentLedger::class);
     }
}