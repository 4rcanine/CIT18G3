<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'semester_id',
        'description',
        'debit',
        'credit',
        'transaction_date',
        'remarks',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    // Relationship to User (Student)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}