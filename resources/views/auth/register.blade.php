<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md mx-auto">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="w-full border rounded px-3 py-2 mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="w-full border rounded px-3 py-2 mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="w-full border rounded px-3 py-2 mt-1"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="w-full border rounded px-3 py-2 mt-1"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-sm" />
        </div>

        <div class="flex items-center justify-between">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="bg-indigo-600 text-white px-4 py-2 rounded">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
