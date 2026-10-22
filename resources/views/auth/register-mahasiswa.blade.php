<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Akun</h2>
        <p class="text-sm text-gray-500">Lengkapi data diri Anda dengan benar.</p>
    </div>

    <form method="POST" action="{{ route('register.mahasiswa.store') }}">
        @csrf

        <!-- NAMA LENGKAP -->
        <div>
            <x-input-label for="name" :value="__('NAMA LENGKAP')" class="font-bold text-xs text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Joko" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NIM & KELAS/PRODI (2 KOLOM) -->
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="nim" :value="__('NIM')" class="font-bold text-xs text-gray-700" />
                <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim')" required placeholder="132xxxx" />
                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="class_prodi" :value="__('KELAS / PRODI')" class="font-bold text-xs text-gray-700" />
                <x-text-input id="class_prodi" class="block mt-1 w-full" type="text" name="class_prodi" :value="old('class_prodi')" required placeholder="K01 / EL" />
                <x-input-error :messages="$errors->get('class_prodi')" class="mt-2" />
            </div>
        </div>

        <!-- EMAIL -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('EMAIL')" class="font-bold text-xs text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="email@itb.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- NO. HP / WHATSAPP -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('NO. HP / WHATSAPP')" class="font-bold text-xs text-gray-700" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required placeholder="0812..." />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- PASSWORD -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('PASSWORD')" class="font-bold text-xs text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('KONFIRMASI PASSWORD')" class="font-bold text-xs text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- TOMBOL DAFTAR & MASUK -->
        <div class="mt-6">
            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-black font-bold rounded-lg shadow transition duration-150">
                Daftar & Masuk
            </button>
        </div>

        <div class="text-center mt-4">
            <span class="text-sm text-gray-600">Sudah punya akun? </span>
            <a href="{{ route('login.mahasiswa') }}" class="text-sm font-bold text-blue-600 hover:underline">
                Login disini
            </a>
        </div>
    </form>
</x-guest-layout>
