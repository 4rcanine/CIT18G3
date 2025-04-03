{{-- resources/views/layouts/header.blade.php --}}
<header class="bg-white dark:bg-gray-800 shadow-md p-4 flex items-center justify-between border-b dark:border-gray-700 fixed w-full z-20 top-0 left-0">
    <!-- Search Bar (Optional - Functionality needs backend) -->
    <div class="relative md:w-1/3 lg:w-1/2">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </span>
        <input class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
               type="text" placeholder="Search">
    </div>

    <!-- User Profile Dropdown -->
    <div class="relative">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    <img class="h-8 w-8 rounded-full object-cover mr-2" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->studentProfile->first_name ?? Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="User avatar">
                    <div class="text-left">
                        <div class="font-medium text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->studentProfile->first_name ?? Auth::user()->name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ ucwords(Auth::user()->studentProfile->year_level ?? 'N/A') }} Year
                        </div>
                    </div>
                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
