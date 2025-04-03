<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Class Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Subject</th>
                                <th class="border p-2">Section</th>
                                <th class="border p-2">Schedule</th>
                                <th class="border p-2">Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule as $class)
                            <tr>
                                <td class="border p-2">{{ $class->subject->code }}</td>
                                <td class="border p-2">{{ $class->section_code }}</td>
                                <td class="border p-2">{{ $class->schedule_info }}</td>
                                <td class="border p-2">{{ $class->instructor_name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
