<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar -->
        @include('sidebar') 

        <!-- Main Content -->
        <div class="flex-1 py-12 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold">
                            Welcome, {{ Auth::user()->studentProfile->first_name ?? 'Student' }}!
                        </h3>

                        <div class="mt-4 space-y-2">
                            <p><strong>Student ID:</strong> {{ Auth::user()->student_id ?? 'N/A' }}</p>
                            <p><strong>Program:</strong> {{ Auth::user()->studentProfile->program->name ?? 'N/A' }}</p>
                            <p><strong>Year Level:</strong> {{ ucfirst(Auth::user()->studentProfile->year_level ?? 'Unknown') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
