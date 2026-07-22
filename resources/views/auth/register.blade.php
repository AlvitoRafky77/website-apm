<x-guest-layout>
    <style>
        .field-hint {
            max-height: 0;
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            font-size: 11px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }
        input:focus ~ .field-hint {
            max-height: 40px;
            opacity: 1;
            transform: translateY(0);
            margin-bottom: 4px;
        }
    </style>

    <a href="{{ route('register') }}" class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-[#CD422B] mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali pilih tipe akun
    </a>

    <div class="text-center mb-6">
        <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full mb-3">Pelanggan Retail (B2C)</span>
        <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Isi data diri Anda untuk mendaftar</p>
    </div>

    <form method="POST" action="{{ route('register.b2c.store') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="contoh: nama@domain.com" autocomplete="username" />
            <div class="field-hint">
                <svg class="w-3.5 h-3.5 text-[#CD422B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Format email harus valid dan mengandung '@' (contoh: nama@domain.com).</span>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- No. HP -->
        <div>
            <x-input-label for="phone" :value="__('Nomor HP')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" placeholder="Contoh: 08123456789" autocomplete="tel" />
            <div class="field-hint">
                <svg class="w-3.5 h-3.5 text-[#CD422B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Nomor HP berupa angka, panjang 10-13 digit.</span>
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <div class="field-hint">
                <svg class="w-3.5 h-3.5 text-[#CD422B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Kata sandi minimal 8 karakter.</span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full text-base mt-2">
                Daftar Sekarang
            </x-primary-button>
        </div>

        <p class="mt-4 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-[#CD422B] hover:text-red-700 transition-colors">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>
