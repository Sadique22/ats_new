<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a class="navbar-brand logo_h" href="{{ url('/') }}"
              ><img src="{{ asset('assets/img/logo-black.png') }}" alt="" height="80px" width="320px" /></a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />
       @if(session()->has('message'))
                    <div class="alert alert-success">
                      <div class="container">
                        {{ session()->get('message') }}
                      </div>
                    </div>
        @endif
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        @if(isset($custom_message))
            <div class="alert alert-success">
                <div class="container">
                {{ $custom_message }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <i class="fa fa-eye float-right password_toggle" id="l_togglePassword"></i>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    <a href="/register" class="text-primary ml-auto" >Register Now</a>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Login') }}
                </x-jet-button>
            </div>
        </form>
    <script type="text/javascript">
    //Toggle Password : Login
    const togglePassword_login = document.querySelector('#l_togglePassword');
    const password_login = document.querySelector('#password');
    togglePassword_login.addEventListener('click', function (e) {
        const type = password_login.getAttribute('type') === 'password' ? 'text' : 'password';
        password_login.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
    </script>
    <style type="text/css">
        i#l_togglePassword {
            margin-top: -30px;
            margin-right: 20px;
        }
    </style>    
    </x-jet-authentication-card>
</x-guest-layout>
