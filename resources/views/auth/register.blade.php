<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- NIS -->
        <div class="mt-4">
            <x-input-label for="nis" value="NIS" />
            <x-text-input id="nis" name="nis" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
        </div>

        <!-- Kelas -->
        <div class="mt-4">
            <x-input-label for="kelas" value="Kelas" />
            <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
        </div>

        <!-- Jurusan -->
        <div class="mt-4">
            <x-input-label for="jurusan" value="Jurusan" />
            <x-text-input id="jurusan" name="jurusan" type="text" class="mt-1 block w-full" />
        </div>

        {{-- Tanggal Lahir --}}
        <div class="mt-4">
            <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
            <x-text-input type="date" name="tanggal_lahir" />
        </div>
 
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>