<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a class="navbar-brand logo_h" href="{{ url('/') }}"
              ><img src="{{ asset('assets/img/logo-black.png') }}" alt="" height="80px" width="320px" /></a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Enter your Registered Email Address below. We will look for your account and send you a password reset email.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}" role="form" data-toggle="validator">
            @csrf

            <div class="block form-group">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="form-control" type="email" name="email" :value="old('email')" placeholder="Enter email" data-error="Please Enter Your Registered E-mail Address" required autofocus />
                 <div class="help-block with-errors"></div>
            </div>
            <div class="text-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Get Password Reset Link') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
