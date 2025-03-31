<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enrollment - Select Subjects') }} ({{ $activeSemester->name }})
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

                    <form method="POST" action="{{ route('enrollment.process') }}">
                        @csrf

                        @if (count($selectableCourses) > 0)
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Select</th>
                                            <th scope="col" class="py-3 px-6">Code</th>
                                            <th scope="col" class="py-3 px-6">Course Name</th>
                                            <th scope="col" class="py-3 px-6">Units</th>
                                            <th scope="col" class="py-3 px-6">Prerequisites</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($selectableCourses as $item)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">
                                                    @if ($item['met_prerequisites'])
                                                        <input type="checkbox" name="course_ids[]" value="{{ $item['course']->id }}"
                                                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                               {{ is_array(old('course_ids')) && in_array($item['course']->id, old('course_ids')) ? 'checked' : '' }}>
                                                    @else
                                                        <span class="text-red-500 text-xs" title="Prerequisites not met">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                    {{ $item['course']->code }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ $item['course']->name }}
                                                </td>
                                                <td class="py-4 px-6">
                                                    {{ number_format($item['course']->units, 1) }}
                                                </td>
                                                <td class="py-4 px-6 text-xs">
                                                    @if (!$item['met_prerequisites'])
                                                        <span class="text-red-500">Requires:
                                                            @foreach($item['missing_prerequisites'] as $missing)
                                                                {{ $missing->code }}{{ !$loop->last ? ', ' : '' }}
                                                            @endforeach
                                                        </span>
                                                    @elseif($item['course']->prerequisites->isNotEmpty())
                                                        {{ $item['course']->prerequisites->pluck('code')->implode(', ') }}
                                                    @else
                                                        None
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button>
                                    {{ __('Proceed to Confirmation') }}
                                </x-primary-button>
                            </div>
                        @else
                            <p>There are currently no courses available for enrollment in your program for this semester, or you have already completed them.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>