<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program; // Import Program model
use App\Models\StudentProfile; // Import StudentProfile model
use App\Providers\RouteServiceProvider; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str; 
use Carbon\Carbon; 

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $programs = Program::orderBy('name')->get();
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:5'],
            'program_id' => ['required', 'exists:programs,id'], 
            'year_level' => ['required', 'in:freshman,sophomore,junior'],
            'birthday' => ['required', 'date', 'before_or_equal:today'],
            'sex' => ['required', 'string', 'max:50'],
            'nationality' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'civil_status' => ['required', 'string', 'max:50'],
        ]);

        // --- Generate Unique Numeric Student ID (6 digits) ---
        do {
            $studentId = rand(100000, 999999); // Generate 6-digit ID
        } while (User::where('student_id', $studentId)->exists());

        // --- Create the User ---
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name, // Concatenated name
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'student_id' => $studentId, // Assign generated numeric student ID
        ]);

        // --- Create Student Profile ---
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

        return redirect(route('dashboard', absolute: false));
    }
}
