<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'semester_id',
        'status',
        'final_grade',
    ];

    // Relationship to User (Student)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Schedule (Class Section)
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Relationship to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}