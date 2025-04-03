<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DropSemesterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Enrollment Routes
    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        Route::get('/select', [EnrollmentController::class, 'showSubjectSelection'])->name('select');
        Route::post('/select', [EnrollmentController::class, 'processSubjectSelection'])->name('process');
        Route::get('/confirm', [EnrollmentController::class, 'showConfirmation'])->name('confirm');
        Route::post('/lock', [EnrollmentController::class, 'lockSubjects'])->name('lock');
        Route::post('/change', [EnrollmentController::class, 'changeSubjects'])->name('change');
    });

    // Payment Info
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.info');

    // Checklist
    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist');

    // Result
    Route::get('/result', [ResultController::class, 'index'])->name('result.index');

    // Notice
    Route::get('/notice', [NoticeController::class, 'index'])->name('notice');

    // Schedule
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

    // Drop Semester
    Route::get('/drop-semester', [DropSemesterController::class, 'index'])->name('semester.drop');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

});

require __DIR__.'/auth.php';
