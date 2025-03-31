<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Remove Original Name field -->
        {{--
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        --}}

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Middle Initial -->
        <div class="mt-4">
            <x-input-label for="middle_initial" :value="__('Middle Initial (Optional)')" />
            <x-text-input id="middle_initial" class="block mt-1 w-full" type="text" name="middle_initial" :value="old('middle_initial')" maxlength="5" autocomplete="additional-name" />
            <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
        </div>

        <!-- Program Dropdown -->
        <div class="mt-4">
            <x-input-label for="program_id" :value="__('Program/Degree')" />
            <select id="program_id" name="program_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="" disabled {{ old('program_id') ? '' : 'selected' }}>-- Select Program --</option>
                @isset($programs) {{-- Check if $programs exists --}}
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->name }} ({{ $program->code }})
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>Could not load programs.</option>
                @endisset
            </select>
            <x-input-error :messages="$errors->get('program_id')" class="mt-2" />
        </div>

        <!-- Year Level Dropdown -->
        <div class="mt-4">
            <x-input-label for="year_level" :value="__('Year Level')" />
            <select id="year_level" name="year_level" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="" disabled {{ old('year_level') ? '' : 'selected' }}>-- Select Year Level --</option>
                <option value="freshman" {{ old('year_level') == 'freshman' ? 'selected' : '' }}>Freshman</option>
                <option value="sophomore" {{ old('year_level') == 'sophomore' ? 'selected' : '' }}>Sophomore</option>
                <option value="junior" {{ old('year_level') == 'junior' ? 'selected' : '' }}>Junior</option>
                </select>
            <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
        </div>

        <!-- Birthday -->
        <div class="mt-4">
            <x-input-label for="birthday" :value="__('Birthday')" />
            <x-text-input id="birthday" class="block mt-1 w-full" type="date" name="birthday" :value="old('birthday')" required autocomplete="bday" />
            <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
        </div>

        <!-- Sex -->
         <div class="mt-4">
             <x-input-label for="sex" :value="__('Sex')" />
             <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="" disabled {{ old('sex') ? '' : 'selected' }}>-- Select Sex --</option>
                 <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                 <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                 <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                 {{-- Add more options if needed --}}
             </select>
             <x-input-error :messages="$errors->get('sex')" class="mt-2" />
         </div>

         <!-- Nationality -->
         <div class="mt-4">
             <x-input-label for="nationality" :value="__('Nationality')" />
             <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality')" required autocomplete="country-name" />
             <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
         </div>

         <!-- Address -->
         <div class="mt-4">
             <x-input-label for="address" :value="__('Address')" />
             <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required autocomplete="street-address">{{ old('address') }}</textarea>
             <x-input-error :messages="$errors->get('address')" class="mt-2" />
         </div>

         <!-- Civil Status -->
         <div class="mt-4">
             <x-input-label for="civil_status" :value="__('Civil Status')" />
              <select id="civil_status" name="civil_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="" disabled {{ old('civil_status') ? '' : 'selected' }}>-- Select Status --</option>
                 <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                 <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                 <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                 <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                 {{-- Add more options if needed --}}
             </select>
             <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
         </div>


        <!-- Email Address (Keep this) -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-input-error :messages="$errors->get('student_number')" class="mt-2 text-red-500" /> {{-- Display student number generation errors --}}
        </div>

        <!-- Password (Keep this) -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password (Keep this) -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>