<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Total Due: ₱{{ number_format($total_due, 2) }}</h3>
                    <p class="mt-2">Please proceed to the cashier for payment.</p>

                    <form action="{{ route('payment.confirm') }}" method="POST">
                        @csrf
                        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">Confirm Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
