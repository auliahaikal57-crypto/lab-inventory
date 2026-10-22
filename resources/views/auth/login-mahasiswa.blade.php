<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- TAB PILIHAN LOGIN -->
    <div class="flex justify-center border-b mb-6">
        <a href="{{ route('login') }}" class="py-2 px-4 text-gray-500 hover:text-indigo-600 transition">
            Login Admin
        </a>
        <span class="py-2 px-4 font-bold border-b-2 border-emerald-600 text-emerald-600 cursor-default">
            Login Mahasiswa
        </span>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email / NPM Mahasiswa -->
        <div>
            <x-input-label for="email" :value="__('Email Mahasiswa')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="@itb.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- TOMBOL DAFTAR & LOG IN -->
        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-emerald-600 hover:text-emerald-800 font-bold" href="{{ route('register.mahasiswa') }}">
                {{ __('Daftar Akun Baru') }}
            </a>

            <div class="flex items-center">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 me-3" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif

                <x-primary-button style="background-color: #059669;">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
