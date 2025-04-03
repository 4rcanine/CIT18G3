<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notice Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-medium">{{ $notice->title }}</h3>
                    <p class="mt-4">{{ $notice->content }}</p>

                    <div class="mt-6">
                        <a href="{{ route('notice.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Back to Notices</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
