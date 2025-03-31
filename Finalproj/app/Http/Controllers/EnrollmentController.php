<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PaymentLedger;
use App\Models\Schedule;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // For logging errors
use Carbon\Carbon; // For date comparisons

class EnrollmentController extends Controller
{
    // Helper function to get the active semester
    private function getActiveSemester()
    {
        // Consider caching this query for performance
        return Semester::where('is_active', true)->first();
    }

    // Helper function to get completed course IDs for the student
    private function getCompletedCourseIds($studentId)
    {
        return Enrollment::where('user_id', $studentId)
            ->where('status', 'completed') // Assuming 'completed' means passed
            // ->where('final_grade', '>=', 3.0) // Add grade condition if needed
            ->join('schedules', 'enrollments.schedule_id', '=', 'schedules.id')
            ->pluck('schedules.course_id') // Get the course ID from the schedule
            ->unique()
            ->toArray();
    }

    /**
     * Show the subject selection page.
     */
    public function showSubjectSelection(Request $request)
    {
        $student = Auth::user();
        $studentProfile = $student->studentProfile()->with('program')->first();

        if (!$studentProfile || !$studentProfile->program) {
            return redirect()->route('dashboard')->with('error', 'Student profile or program not found. Please complete your profile.');
        }

        $activeSemester = $this->getActiveSemester();
        if (!$activeSemester) {
            return redirect()->route('dashboard')->with('error', 'No active enrollment semester found.');
        }

        // Check if enrollment period is open
        $now = Carbon::now();
        if (!$now->between($activeSemester->enrollment_start, $activeSemester->enrollment_end)) {
             return redirect()->route('dashboard')->with('error', 'Enrollment period is not currently open.');
        }

        $completedCourseIds = $this->getCompletedCourseIds($student->id);

        // Get schedules for the student's program in the active semester
        // Eager load course and its prerequisites
        $availableSchedules = Schedule::where('semester_id', $activeSemester->id)
            ->whereHas('course', function ($query) use ($studentProfile) {
                $query->where('program_id', $studentProfile->program_id); // Filter by student's program
            })
            ->with(['course.prerequisites'])
            ->get();

        $selectableCourses = [];
        $processedCourseIds = []; // Keep track of courses already added to avoid duplicates

        foreach ($availableSchedules as $schedule) {
            $course = $schedule->course;

            // Skip if course already processed or completed by student
            if (in_array($course->id, $processedCourseIds) || in_array($course->id, $completedCourseIds)) {
                continue;
            }

            // Check prerequisites
            $prerequisites = $course->prerequisites;
            $metPrerequisites = true;
            $missingPrerequisites = [];
            if ($prerequisites->isNotEmpty()) {
                $prerequisiteIds = $prerequisites->pluck('id')->toArray();
                $missingPrerequisites = array_diff($prerequisiteIds, $completedCourseIds);
                if (!empty($missingPrerequisites)) {
                    $metPrerequisites = false;
                }
            }

            // Get IDs of missing prerequisite courses to fetch their names/codes later if needed
            $missingPrerequisiteDetails = [];
            if (!$metPrerequisites) {
                $missingPrerequisiteDetails = Course::whereIn('id', $missingPrerequisites)->get(['id', 'code', 'name']);
            }


            $selectableCourses[] = [
                'course' => $course,
                'met_prerequisites' => $metPrerequisites,
                'missing_prerequisites' => $missingPrerequisiteDetails, // Pass details of missing ones
            ];
            $processedCourseIds[] = $course->id; // Mark course as processed
        }

        // Sort courses alphabetically by code or name
        usort($selectableCourses, function($a, $b) {
            return strcmp($a['course']['code'], $b['course']['code']);
        });

        return view('enrollment.select', compact('selectableCourses', 'activeSemester'));
    }

    /**
     * Process the selected subjects and create preliminary enrollment records.
     */
    public function processSubjectSelection(Request $request)
    {
        $request->validate([
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'required|integer|exists:courses,id',
        ]);

        $student = Auth::user();
        $activeSemester = $this->getActiveSemester();
        if (!$activeSemester) {
            return back()->with('error', 'No active enrollment semester found.');
        }
         // Double check enrollment period
         $now = Carbon::now();
         if (!$now->between($activeSemester->enrollment_start, $activeSemester->enrollment_end)) {
              return back()->with('error', 'Enrollment period has closed.');
         }

        $selectedCourseIds = $request->input('course_ids');
        $completedCourseIds = $this->getCompletedCourseIds($student->id);

        // Configuration for cost (move to config/app.php or a settings table later)
        $costPerUnit = config('app.cost_per_unit', 500.00); // Default to 500 if not set

        DB::beginTransaction();
        try {
            $totalUnits = 0;
            $enrollmentsToCreate = [];

             // --- Prerequisite Re-validation (Server-side) ---
             $courses = Course::with('prerequisites')->whereIn('id', $selectedCourseIds)->get();
             foreach ($courses as $course) {
                 if (in_array($course->id, $completedCourseIds)) {
                    DB::rollBack();
                    return back()->with('error', "You cannot re-enroll in the completed course: {$course->code}. Please uncheck it.")->withInput();
                 }
                 $prerequisiteIds = $course->prerequisites->pluck('id')->toArray();
                 $missingPrerequisites = array_diff($prerequisiteIds, $completedCourseIds);
                 if (!empty($missingPrerequisites)) {
                     $missingCodes = Course::whereIn('id', $missingPrerequisites)->pluck('code')->implode(', ');
                     DB::rollBack();
                     return back()->with('error', "Prerequisites not met for {$course->code}. Missing: {$missingCodes}. Please uncheck it.")->withInput();
                 }
             }
            // --- End Prerequisite Re-validation ---

            // Clear any previous 'selected' enrollments and related 'Tuition Fee' debits for this semester
             Enrollment::where('user_id', $student->id)
                 ->where('semester_id', $activeSemester->id)
                 ->where('status', 'selected')
                 ->delete();
             PaymentLedger::where('user_id', $student->id)
                ->where('semester_id', $activeSemester->id)
                // Be specific to avoid deleting other debits if possible
                ->where('description', 'LIKE', 'Tuition Fee %')
                ->where('credit', 0) // Ensure it's a debit entry
                ->delete();


            foreach ($selectedCourseIds as $courseId) {
                $course = $courses->find($courseId); // Get course from pre-validated collection
                if (!$course) continue; // Should not happen due to validation

                // Find *one* available schedule for this course in the active semester.
                // !! IMPORTANT !! This is a simplification.
                // Real-world scenario: Show sections to user or use complex balancing logic.
                $schedule = Schedule::where('course_id', $course->id)
                                    ->where('semester_id', $activeSemester->id)
                                    // Add slot checking logic here if implemented: ->where('slots_taken', '<', 'slots')
                                    ->first();

                if (!$schedule) {
                    DB::rollBack();
                    return back()->with('error', "No available schedule found for course {$course->code}. Please contact admin.")->withInput();
                }

                $enrollmentsToCreate[] = [
                    'user_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'semester_id' => $activeSemester->id,
                    'status' => 'selected',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $totalUnits += $course->units;
            }

            // Bulk insert enrollments for efficiency
            if (!empty($enrollmentsToCreate)) {
                Enrollment::insert($enrollmentsToCreate);
            } else {
                 DB::rollBack(); // Should not happen if validation passed, but safeguard
                 return back()->with('error', 'No valid courses were selected.')->withInput();
            }

            // Create Payment Ledger entry for tuition fee
            $totalTuition = $totalUnits * $costPerUnit;
            if ($totalTuition > 0) {
                PaymentLedger::create([
                    'user_id' => $student->id,
                    'semester_id' => $activeSemester->id,
                    'description' => "Tuition Fee - {$activeSemester->name}",
                    'debit' => $totalTuition,
                    'credit' => 0,
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Enrollment Processing Error for User ID {$student->id}: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred while processing your selection. Please try again.')->withInput();
        }

        return redirect()->route('enrollment.confirm');
    }

    /**
     * Show the confirmation page before locking subjects.
     */
    public function showConfirmation(Request $request)
    {
         $student = Auth::user();
         $activeSemester = $this->getActiveSemester();
         if (!$activeSemester) {
             return redirect()->route('dashboard')->with('error', 'No active enrollment semester found.');
         }

         // Fetch the 'selected' enrollments for this student and semester
         $selectedEnrollments = Enrollment::where('user_id', $student->id)
             ->where('semester_id', $activeSemester->id)
             ->where('status', 'selected')
             ->with(['schedule.course']) // Eager load schedule and course details
             ->get();

         if ($selectedEnrollments->isEmpty()) {
             // If no selected enrollments, maybe they came here directly or cleared selection
             return redirect()->route('enrollment.select')->with('info', 'Please select your subjects first.');
         }

         $totalUnits = $selectedEnrollments->sum(function ($enrollment) {
             return $enrollment->schedule->course->units ?? 0;
         });

         // Calculate total payable by summing debits for the semester
         $totalPayable = PaymentLedger::where('user_id', $student->id)
             ->where('semester_id', $activeSemester->id)
             ->sum('debit');
        // You might want to subtract credits later for a "Balance Due" figure
         $totalPaid = PaymentLedger::where('user_id', $student->id)
             ->where('semester_id', $activeSemester->id)
             ->sum('credit');
         $balanceDue = $totalPayable - $totalPaid;


         return view('enrollment.confirm', compact(
            'selectedEnrollments',
            'activeSemester',
            'totalUnits',
            'totalPayable', // Pass total payable
            'balanceDue' // Pass balance due
        ));
    }

    /**
     * Lock the selected subjects.
     */
    public function lockSubjects(Request $request)
    {
        $student = Auth::user();
        $activeSemester = $this->getActiveSemester();
        if (!$activeSemester) {
            return redirect()->route('dashboard')->with('error', 'No active enrollment semester found.');
        }

        // Find 'selected' enrollments and update their status to 'locked'
        $updatedCount = Enrollment::where('user_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->where('status', 'selected')
            ->update(['status' => 'locked']); // Change status to 'locked'

        if ($updatedCount > 0) {
            return redirect()->route('dashboard')->with('success', 'Enrollment locked successfully! Please check your schedule.');
            // Or redirect to a schedule view: redirect()->route('schedule.view')
        } else {
            // This might happen if they try to lock already locked subjects or have none selected
            return redirect()->route('enrollment.select')->with('info', 'No subjects were selected or they were already locked.');
        }
    }

     /**
     * Allow student to go back and change subjects (clears current selection).
     */
    public function changeSubjects(Request $request)
    {
        $student = Auth::user();
        $activeSemester = $this->getActiveSemester();
         if (!$activeSemester) {
             return redirect()->route('dashboard')->with('error', 'No active enrollment semester found.');
         }

        DB::beginTransaction();
        try {
            // Delete 'selected' enrollments
             Enrollment::where('user_id', $student->id)
                 ->where('semester_id', $activeSemester->id)
                 ->where('status', 'selected')
                 ->delete();

             // Delete the corresponding 'Tuition Fee' debit entry
             // Make sure this logic correctly identifies the entry made during processSubjectSelection
             PaymentLedger::where('user_id', $student->id)
                 ->where('semester_id', $activeSemester->id)
                 ->where('description', 'LIKE', "Tuition Fee - {$activeSemester->name}") // Match description
                 ->where('credit', 0) // It's a debit
                 ->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Enrollment Change Error for User ID {$student->id}: " . $e->getMessage());
             return redirect()->route('enrollment.confirm')->with('error', 'Could not clear selection. Please try again.');
        }

        return redirect()->route('enrollment.select')->with('info', 'Your previous selection has been cleared. Please select subjects again.');
    }
}