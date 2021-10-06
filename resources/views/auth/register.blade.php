<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a class="navbar-brand logo_h" href="{{ url('/') }}"
              ><img src="{{ asset('assets/img/logo-black.png') }}" alt="" height="80px" width="320px" /></a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="contact" value="{{ __('Contact') }}" />
                <x-jet-input id="contact" class="block mt-1 w-full" type="contact" name="contact" :value="old('contact')" />
            </div>
            <div class="mt-4">
                <x-jet-label for="user_type"  value="{{ __('I Want to Register as:') }}" />
                    <select class="form-control" name="user_type" data-style="select-with-transition" title="I want to join as" data-size="7">
                          <option value="">Select</option>
                          <option value="3">Student</option>
                          <option value="2">Teacher</option>
                          <option value="4">Parent</option>
                    </select>
            </div>
            
            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            {{-- <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div> --}}

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
