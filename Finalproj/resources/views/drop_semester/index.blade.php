<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Drop Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Are you sure you want to drop this semester?</h3>

                    <form method="POST" action="{{ route('drop_semester.confirm') }}">
                        @csrf
                        <div class="mt-4">
                            <x-primary-button>Confirm Drop</x-primary-button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
