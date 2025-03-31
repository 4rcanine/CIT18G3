<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    // Relationship to StudentProfiles
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }

    // Relationship to Courses (if one program -> many courses)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}