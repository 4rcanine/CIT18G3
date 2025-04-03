@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto my-10 p-6 bg-white border rounded shadow-md">
    <h2 class="text-2xl font-bold text-center mb-4">Available Courses</h2>

    @foreach($courses as $course)
        <div class="mb-4 p-4 border rounded">
            <h3 class="text-xl font-semibold">{{ $course->name }}</h3>
            <p class="text-gray-700">{{ $course->description }}</p>
            <p><strong>Units:</strong> {{ $course->units }}</p>
            <p><strong>Program:</strong> {{ $course->program->name }}</p>  <!-- Display the program name -->
            <a href="{{ route('courses.show', $course->id) }}" class="text-blue-500">View Details</a>

            <form method="POST" action="{{ route('courses.enroll', $course->id) }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Enroll Now
                </button>
            </form>
        </div>
    @endforeach
</div>
@endsection
