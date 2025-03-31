<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enrollment - Confirm Subjects') }} ({{ $activeSemester->name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Display Session Messages --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                     @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif
                     @if(session('info'))
                        <div class="mb-4 font-medium text-sm text-blue-600 dark:text-blue-400">
                            {{ session('info') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-medium mb-4">Selected Subjects:</h3>

                    @if ($selectedEnrollments->isNotEmpty())
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-6">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Code</th>
                                        <th scope="col" class="py-3 px-6">Course Name</th>
                                        <th scope="col" class="py-3 px-6">Units</th>
                                        <th scope="col" class="py-3 px-6">Section</th>
                                        <th scope="col" class="py-3 px-6">Schedule</th>
                                        <th scope="col" class="py-3 px-6">Instructor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedEnrollments as $enrollment)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $enrollment->schedule->course->code ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $enrollment->schedule->course->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ number_format($enrollment->schedule->course->units ?? 0, 1) }}
                                            </td>
                                             <td class="py-4 px-6">
                                                {{ $enrollment->schedule->section_code ?? 'N/A' }}
                                            </td>
                                             <td class="py-4 px-6">
                                                {{ $enrollment->schedule->schedule_info ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $enrollment->schedule->instructor_name ?? 'TBA' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                     <tr class="font-semibold bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <td colspan="2" class="py-3 px-6 text-right">Total Units:</td>
                                        <td class="py-3 px-6">{{ number_format($totalUnits, 1) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-6 p-4 border border-yellow-300 bg-yellow-50 dark:bg-gray-700 dark:border-yellow-600 rounded-md">
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                <span class="font-bold">Assessment:</span><br>
                                Total Payable for {{ $activeSemester->name }}: <span class="font-semibold">₱{{ number_format($totalPayable, 2) }}</span> <br>
                                Current Balance Due: <span class="font-semibold">₱{{ number_format($balanceDue, 2) }}</span>
                            </p>
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-semibold">
                                Please Note: Balance must be settled face-to-face at the cashier. Locking subjects confirms your enrollment for assessment purposes.
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                             {{-- Form to Change Subjects --}}
                             <form method="POST" action="{{ route('enrollment.change') }}" class="inline">
                                @csrf
                                <x-secondary-button type="submit" onclick="return confirm('Are you sure you want to clear your current selection and go back?');">
                                    {{ __('Change Subjects') }}
                                </x-secondary-button>
                            </form>

                            {{-- Form to Lock Subjects --}}
                            <form method="POST" action="{{ route('enrollment.lock') }}" class="inline">
                                @csrf
                                <x-primary-button type="submit" onclick="return confirm('Are you sure you want to lock these subjects? You may not be able to change them easily afterwards.');">
                                    {{ __('Lock Subjects') }}
                                </x-primary-button>
                            </form>
                        </div>

                    @else
                        <p>No subjects selected for confirmation.</p>
                        <div class="mt-4">
                             <a href="{{ route('enrollment.select') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                {{ __('Go Back to Selection') }}
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>