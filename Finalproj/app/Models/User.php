<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id', // Numeric Student ID
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically generate student_id before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->student_id = static::generateStudentID();
        });
    }

    /**
     * Generate a unique numeric student ID (6 digits).
     */
    private static function generateStudentID()
    {
        do {
            $student_id = rand(100000, 999999); // 6-digit random number
        } while (self::where('student_id', $student_id)->exists());

        return $student_id;
    }

    // Relationship to StudentProfile
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    // Relationship to Enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relationship to PaymentLedger
    public function paymentLedgerEntries()
    {
        return $this->hasMany(PaymentLedger::class);
    }
}
