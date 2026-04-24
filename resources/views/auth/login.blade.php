<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- ===== ERROR ALERT BANNER ===== --}}
    @if ($errors->any())
    <div id="loginErrorAlert"
         class="mb-5 flex items-start gap-3.5 bg-red-50 border border-red-200 rounded-2xl px-4 py-4 animate-shake"
         role="alert">
        <!-- Icon -->
        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-red-100 flex items-center justify-center mt-0.5">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <!-- Message -->
        <div class="flex-1">
            <p class="text-sm font-bold text-red-700 mb-0.5">Login Gagal</p>
            <p class="text-sm text-red-600 leading-relaxed">
                @if ($errors->has('email'))
                    {{ $errors->first('email') }}
                @else
                    Email atau kata sandi yang Anda masukkan salah. Silakan periksa kembali.
                @endif
            </p>
        </div>
        <!-- Close button -->
        <button type="button" onclick="document.getElementById('loginErrorAlert').remove()"
                class="flex-shrink-0 text-red-400 hover:text-red-600 transition mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15%       { transform: translateX(-6px); }
            30%       { transform: translateX(6px); }
            45%       { transform: translateX(-4px); }
            60%       { transform: translateX(4px); }
            75%       { transform: translateX(-2px); }
            90%       { transform: translateX(2px); }
        }
        .animate-shake { animation: shake 0.5s ease-in-out; }
    </style>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full focus:border-brand focus:ring-brand {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-500' : '' }}"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Kata Sandi')" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-brand hover:text-brand-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <div class="relative mt-1">
                <x-text-input id="password"
                    class="block w-full pr-12 focus:border-brand focus:ring-brand {{ $errors->any() ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-500' : '' }}"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
                <!-- Toggle show/hide password -->
                <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400 hover:text-gray-600 transition"
                        aria-label="Toggle password visibility">
                    <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full text-base">
                Masuk
            </x-primary-button>
        </div>
        
        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-accent hover:text-orange-600 transition-colors">Daftar sekarang</a>
        </p>
    </form>

    <script>
        // Toggle show/hide password
        (function() {
            var btn    = document.getElementById('togglePassword');
            var input  = document.getElementById('password');
            var eyeO   = document.getElementById('eyeOpen');
            var eyeC   = document.getElementById('eyeClosed');
            if (!btn || !input) return;
            btn.addEventListener('click', function() {
                var isHidden = input.type === 'password';
                input.type   = isHidden ? 'text' : 'password';
                eyeO.classList.toggle('hidden', isHidden);
                eyeC.classList.toggle('hidden', !isHidden);
            });
        })();
    </script>
</x-guest-layout>
