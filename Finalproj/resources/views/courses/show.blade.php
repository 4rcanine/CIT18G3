@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-10 p-6 bg-white border rounded shadow-md">
    <h2 class="text-2xl font-bold text-center mb-4">{{ $course->name }}</h2>

    <p class="text-gray-700">{{ $course->description }}</p>
    <p><strong>Units:</strong> {{ $course->units }}</p>
    <p><strong>Program:</strong> {{ $course->program->name }}</p>

    <h3 class="text-lg font-semibold mt-6">Prerequisites</h3>
    <ul class="list-disc ml-5">
        @forelse($course->prerequisites as $prerequisite)
            <li>{{ $prerequisite->name }}</li>
        @empty
            <li>No prerequisites for this course.</li>
        @endforelse
    </ul>

    <h3 class="text-lg font-semibold mt-6">Schedules</h3>
    @if($course->schedules->isEmpty())
        <p>No schedules available for this course.</p>
    @else
        <ul class="list-disc ml-5">
            @foreach($course->schedules as $schedule)
                <li>{{ $schedule->day_of_week }} - {{ $schedule->start_time }} to {{ $schedule->end_time }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('courses.enroll', $course->id) }}" class="mt-4">
        @csrf
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Enroll Now
        </button>
    </form>
</div>
@endsection
