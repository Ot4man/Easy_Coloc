<x-guest-layout>
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Inscription</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Rejoignez EasyColoc et simplifiez votre vie à plusieurs</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $email ?? '')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center text-lg py-3.5">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="mt-8 text-center text-sm">
            <span class="text-slate-500">{{ __('Already registered?') }}</span>
            <a class="font-semibold text-cyan-600 hover:text-cyan-800 ml-1 transition-colors" href="{{ route('login') }}">
                Connectez-vous ici
            </a>
        </div>
    </form>
</x-guest-layout>
