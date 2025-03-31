<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program; // Import Program model
use App\Models\StudentProfile; // Import StudentProfile model
use App\Providers\RouteServiceProvider; // Make sure this line exists
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str; // For Str::upper
use Carbon\Carbon; // For Year

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Fetch programs to populate the dropdown
        $programs = Program::orderBy('name')->get();
        // Pass programs to the view
        return view('auth.register', compact('programs'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Remove original 'name' validation if not used directly in users table
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Add validation for new fields
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:5'],
            'program_id' => ['required', 'exists:programs,id'], // Ensure program exists
            'year_level' => ['required', 'in:freshman,sophomore,junior'],
            'birthday' => ['required', 'date', 'before_or_equal:today'],
            'sex' => ['required', 'string', 'max:50'],
            'nationality' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'civil_status' => ['required', 'string', 'max:50'],
        ]);

        // --- Student Number Generation Logic ---
        // Example: YYYY-[ProgramCode]-[Sequence] - Needs refinement for robust sequencing
        $programCode = Program::find($request->program_id)->code;
        $currentYear = Carbon::now()->year;
        // Basic sequence - WARNING: This is NOT robust for concurrent requests.
        // Consider a dedicated sequence table or atomic increments in production.
        $lastStudent = User::where('student_number', 'like', $currentYear.'-'.$programCode.'-%')
                            ->orderBy('student_number', 'desc')
                            ->first();
        $sequence = 1;
        if ($lastStudent && $lastStudent->student_number) {
            $parts = explode('-', $lastStudent->student_number);
            if (count($parts) > 1) { // Basic check if format is as expected
                 $lastSequence = (int)end($parts);
                 $sequence = $lastSequence + 1;
            }
        }
        $studentNumber = sprintf('%d-%s-%04d', $currentYear, $programCode, $sequence);

        // Ensure generated number is truly unique (retry or fail if collision)
        while (User::where('student_number', $studentNumber)->exists()) {
            // Very basic collision handling: just increment sequence and regenerate
            // In high-traffic apps, this could loop; more robust handling needed.
            $sequence++;
            $studentNumber = sprintf('%d-%s-%04d', $currentYear, $programCode, $sequence);
            // Add a safeguard to prevent infinite loops in extreme cases
            if ($sequence > 9999) {
                 return back()->withErrors(['student_number' => 'Could not generate unique student number after multiple attempts. Please contact admin.'])->withInput();
            }
        }
        // --- End Student Number Generation ---

        // Create the User
        // Decide how to handle 'name': concatenate, use email, or make nullable
        // If StudentProfile holds the definitive name, 'name' here might be redundant
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name, // Concatenated name
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'student_number' => $studentNumber, // Assign the generated number
        ]);

        // Create the Student Profile using the relationship
        $user->studentProfile()->create([
            'year_level' => $request->year_level,
            'program_id' => $request->program_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_initial' => $request->middle_initial ? Str::upper($request->middle_initial) : null,
            'birthday' => $request->birthday,
            'sex' => $request->sex,
            'nationality' => $request->nationality,
            'address' => $request->address,
            'civil_status' => $request->civil_status,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect using RouteServiceProvider constant or specific route name
        // return redirect(RouteServiceProvider::HOME);
         return redirect(route('dashboard', absolute: false)); // Keep original redirect
    }
}