<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Program; // Include Program model if needed
use App\Models\Schedule; // Include Schedule model if needed

class CourseController extends Controller
{
    // Display all courses
    public function index()
    {
        $courses = Course::with('program', 'schedules')->get(); // Fetch courses with their program and schedules
        return view('courses.index', compact('courses'));
    }

    // Show the details of a single course
    public function show($id)
    {
        $course = Course::with('program', 'schedules', 'prerequisites')->findOrFail($id);  // Fetch course with related program, schedules, and prerequisites
        return view('courses.show', compact('course'));
    }

    // Enroll the student in a course
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        $student = auth()->user();  

        // Enroll the student in the course (if not already enrolled)
        // This assumes you have an Enrollment model to track enrollments
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        return redirect()->route('courses.index')->with('success', 'Successfully enrolled in ' . $course->name);
    }
}

