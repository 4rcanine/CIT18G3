<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
        'instructor_name',
        'section_code',
        'schedule_info',
        'slots',
    ];

    // Relationship to Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Relationship to Enrollments (students enrolled in this section)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}