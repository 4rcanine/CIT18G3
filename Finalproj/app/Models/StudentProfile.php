<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year_level',
        'program_id',
        'first_name',
        'last_name',
        'middle_initial',
        'birthday',
        'sex',
        'nationality',
        'address',
        'civil_status',
    ];

    // Relationship back to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Accessor to get full name easily
    public function getFullNameAttribute() {
        return "{$this->last_name}, {$this->first_name} {$this->middle_initial}";
    }
}