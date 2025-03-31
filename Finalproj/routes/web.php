<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController; // Add this
use Illuminate\Support\Facades\Route;

// ... other use statements

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index']) // Point to controller
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Enrollment Routes ---
    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        // Show subject selection page
        Route::get('/select', [EnrollmentController::class, 'showSubjectSelection'])->name('select');
        // Process the selected subjects
        Route::post('/select', [EnrollmentController::class, 'processSubjectSelection'])->name('process');
        // Show confirmation page
        Route::get('/confirm', [EnrollmentController::class, 'showConfirmation'])->name('confirm');
        // Lock the subjects
        Route::post('/lock', [EnrollmentController::class, 'lockSubjects'])->name('lock');
        // Go back to change subjects (clears current selection)
        Route::post('/change', [EnrollmentController::class, 'changeSubjects'])->name('change');
    });
    // --- End Enrollment Routes ---

    // --- Add other student routes later ---
    // Route::get('/schedule', [ScheduleController::class, 'viewSchedule'])->name('schedule.view');
    // Route::get('/account/financial', [AccountController::class, 'financials'])->name('account.financials');
    // Route::get('/academics/grades', [AcademicsController::class, 'grades'])->name('academics.grades');
    // Route::get('/academics/checklist', [AcademicsController::class, 'checklist'])->name('academics.checklist');

});

require __DIR__.'/auth.php';