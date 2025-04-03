<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\PaymentLedger;
use App\Models\Notice;
use Carbon\Carbon; // For date filtering

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = Auth::user()->load('studentProfile.program'); // Load profile and program relationship

        // --- Get Active Semester (Handle case where none is active) ---
        $activeSemester = Semester::where('is_active', true)->first();
        $semesterId = $activeSemester?->id; // Use null safe operator

        // --- Finance Data ---
        $totalPayable = 0;
        $totalPaid = 0;
        if ($semesterId) {
            $totalPayable = PaymentLedger::where('user_id', $student->id)
                                        ->where('semester_id', $semesterId)
                                        ->sum('debit');
            $totalPaid = PaymentLedger::where('user_id', $student->id)
                                        ->where('semester_id', $semesterId)
                                        ->sum('credit');
        }
        // Calculate 'Others' if needed, e.g., balance or specific fees
        $balanceDue = $totalPayable - $totalPaid;

        // --- Enrolled/Locked Courses for Active Semester ---
        $enrolledCourses = collect(); // Default to empty collection
        $instructors = [];
        if ($semesterId) {
            $enrolledCourses = Enrollment::where('user_id', $student->id)
                ->where('semester_id', $semesterId)
                ->whereIn('status', ['locked', 'enrolled']) // Show locked or officially enrolled
                ->with(['schedule.course', 'schedule']) // Eager load details
                ->get();

            // Extract unique instructors (simplified)
            $instructors = $enrolledCourses->map(function ($enrollment) {
                return $enrollment->schedule?->instructor_name;
            })->filter()->unique()->values()->all();
        }

        // --- Notices ---
        $now = Carbon::now();
        $notices = Notice::where('publish_date', '<=', $now)
                    ->where(function ($query) use ($now) {
                        $query->whereNull('expiry_date')
                              ->orWhere('expiry_date', '>=', $now);
                    })
                    ->orderBy('publish_date', 'desc')
                    ->take(5) // Limit notices displayed
                    ->get();

        // --- Pass Data to the View ---
        return view('dashboard', [
            'student' => $student, // Includes profile, student_number, program, year_level
            'totalPayable' => $totalPayable,
            'totalPaid' => $totalPaid,
            'balanceDue' => $balanceDue, // Pass balance due
            'enrolledCourses' => $enrolledCourses,
            'instructors' => $instructors, // Pass instructor names
            'notices' => $notices,
            'activeSemesterName' => $activeSemester?->name ?? 'N/A' // Pass semester name
        ]);
    }
}
