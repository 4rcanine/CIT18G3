<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'units',
        'program_id', // Include if one-to-many with Program
    ];

    // Relationship to Program (if one program -> many courses)
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relationship to Schedules (One Course has many Schedule sections)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Relationship for Prerequisites (Courses this course requires)
    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'course_id', 'prerequisite_course_id');
    }

    // Relationship for Prerequisite For (Courses that require this course)
    public function prerequisiteFor()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisite', 'prerequisite_course_id', 'course_id');
    }
}