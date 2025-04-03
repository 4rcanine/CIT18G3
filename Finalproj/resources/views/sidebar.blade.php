{{-- resources/views/layouts/sidebar.blade.php --}}
<div x-data="{ open: true }" class="flex h-screen">
    <!-- Sidebar -->
    <div :class="open ? 'w-64' : 'w-16'" class="bg-purple-700 text-white fixed inset-y-0 left-0 transition-all duration-300">
        <!-- Sidebar Content -->
        <div class="flex flex-col h-full">
            <!-- Logo / Branding -->
            <div class="flex items-center justify-center h-20 border-b border-purple-800">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    {{-- Academic Cap Icon (Heroicon: academic-cap) --}}
                    <img
                        src="https://www.svgrepo.com/show/489120/school.svg"
                        alt="School"
                        class="w-20 h-20"/>
                    <span class="ml-2 text-lg font-bold">{{ config('app.name', 'School Portal') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('dashboard') ? 'bg-purple-800' : '' }}">
                    {{-- Dashboard Icon --}}
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    <span class="mx-3">Dashboard</span>
                </a>

                <a href="{{ route('enrollment.select') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('enrollment.*') ? 'bg-purple-800' : '' }}">
                   <img
                        src="https://www.svgrepo.com/show/57971/register.svg"
                        alt="Enroll"
                        class="w-6 h-6"/>
                    <span class="mx-3">Enrollment</span>
                </a>

                <a href="{{ route('payment.info') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('payment.*') ? 'bg-purple-800' : '' }}">
                    <img
                    src="https://www.svgrepo.com/show/442554/pay.svg"
                    alt="Payment Info Icon"
                    class="w-6 h-6"  {{-- Apply size classes here --}}
                    />
                    <span class="mx-3">Payment Info</span>
                </a>

                <a href="{{ route('courses.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('courses.*') ? 'bg-purple-800' : '' }}">
                <img
                    src="https://www.svgrepo.com/show/501512/collection.svg"
                    alt="Course Icon"
                    class="w-6 h-6"  {{-- Apply size classes here --}}
                    />
                    <span class="mx-3">Courses</span>
                </a>

                <a href="{{ route('semester.drop') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('semester.drop') ? 'bg-purple-800' : '' }}">
                <img
                     src="https://www.svgrepo.com/show/59220/not-allowed-symbol.svg"
                     alt="Drop Icon"
                     class="w-6 h-6"
                    />
                    <span class="mx-3">Drop Semester</span>
                </a>

                <a href="{{ route('result.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('result.*') ? 'bg-purple-800' : '' }}">
                <img
                    src="https://www.svgrepo.com/show/302832/report-file.svg"
                    alt="Result"
                    class="w-6 h-6"
                    />
                    <span class="mx-3">Result</span>
                </a>

                
                <a href="{{ route('notice') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('result.*') ? 'bg-purple-800' : '' }}">
                <img
                    src="https://www.svgrepo.com/show/231025/notification-bell.svg"
                    alt="Result"
                    class="w-6 h-6"
                    />
                    <span class="mx-3">Notice</span>
                </a>

                <a href="{{ route('schedule.index') }}" class="flex items-center px-6 py-2 mt-4 text-gray-100 hover:bg-purple-600 {{ request()->routeIs('schedule.*') ? 'bg-purple-800' : '' }}">
                <img
                    src="https://www.svgrepo.com/show/447776/schedule.svg"
                    alt="Schedule"
                    class="w-6 h-6"/>
                    <span class="mx-3">Schedule</span>
                </a>
            </nav>

            <!-- Logout Link -->
            <div class="px-6 py-4 border-t border-purple-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="flex items-center text-gray-100 hover:bg-purple-600 px-6 py-2 rounded">
                        {{-- Logout Icon --}}
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span class="mx-3">Logout</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content (Dashboard or other pages) -->
    <div class="flex-1 ml-64">
        @yield('content')
    </div>
</div>
