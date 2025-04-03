<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Checklist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Subjects to be Taken:</h3>

                    @if($subjects->isEmpty())
                        <p>You have no subjects in your checklist yet. Please go to your enrollment page to add subjects.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-6">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Code</th>
                                        <th scope="col" class="py-3 px-6">Course Name</th>
                                        <th scope="col" class="py-3 px-6">Units</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $subject->code }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $subject->name }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ number_format($subject->units, 1) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('enrollment.select') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Go to Enrollment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
