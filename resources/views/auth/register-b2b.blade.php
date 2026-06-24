<x-guest-b2b-layout>

    {{-- Display errors summary --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-red-700 mb-1">Harap perbaiki kesalahan berikut:</p>
                <ul class="text-sm text-red-600 space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register.b2b.store') }}" enctype="multipart/form-data" id="form-b2b">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===================== LEFT: 2 COL MAIN FORM ===================== --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- SECTION 1: Data Perusahaan --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-sm text-[#CD422B] uppercase tracking-wider flex items-center gap-2 mb-5">
                        <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        Data Perusahaan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama Perusahaan --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text"
                                name="company_name" :value="old('company_name')" required
                                placeholder="Contoh: PT Maju Bersama" />
                            <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
                        </div>

                        {{-- Jenis Perusahaan --}}
                        <div>
                            <x-input-label for="company_type" :value="__('Jenis Perusahaan')" />
                            <select id="company_type" name="company_type" required
                                class="block mt-1 w-full border-gray-300 focus:border-[#CD422B] focus:ring-[#CD422B] rounded-md shadow-sm text-sm">
                                @foreach(['PT','CV','UD','Firma','Koperasi','Perorangan','Lainnya'] as $type)
                                    <option value="{{ $type }}" {{ old('company_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('company_type')" class="mt-1" />
                        </div>

                        {{-- NPWP --}}
                        <div>
                            <x-input-label for="npwp" :value="__('NPWP Perusahaan')" />
                            <x-text-input id="npwp" class="block mt-1 w-full" type="text"
                                name="npwp" :value="old('npwp')"
                                placeholder="00.000.000.0-000.000" />
                            <p class="text-xs text-gray-400 mt-1">Opsional</p>
                            <x-input-error :messages="$errors->get('npwp')" class="mt-1" />
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="company_address" :value="__('Alamat Perusahaan')" />
                            <textarea id="company_address" name="company_address" rows="3" required
                                class="block mt-1 w-full border-gray-300 focus:border-[#CD422B] focus:ring-[#CD422B] rounded-md shadow-sm text-sm"
                                placeholder="Jalan, Kelurahan, Kecamatan ...">{{ old('company_address') }}</textarea>
                            <x-input-error :messages="$errors->get('company_address')" class="mt-1" />
                        </div>

                        {{-- Kota --}}
                        <div>
                            <x-input-label for="company_city" :value="__('Kota')" />
                            <x-text-input id="company_city" class="block mt-1 w-full" type="text"
                                name="company_city" :value="old('company_city')" placeholder="Pekanbaru" />
                            <x-input-error :messages="$errors->get('company_city')" class="mt-1" />
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <x-input-label for="company_province" :value="__('Provinsi')" />
                            <x-text-input id="company_province" class="block mt-1 w-full" type="text"
                                name="company_province" :value="old('company_province')" placeholder="Riau" />
                            <x-input-error :messages="$errors->get('company_province')" class="mt-1" />
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Data PIC --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-sm text-[#CD422B] uppercase tracking-wider flex items-center gap-2 mb-5">
                        <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        Person in Charge (PIC)
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="pic_name" :value="__('Nama PIC')" />
                            <x-text-input id="pic_name" class="block mt-1 w-full" type="text"
                                name="pic_name" :value="old('pic_name')" required
                                placeholder="Nama lengkap penanggung jawab" />
                            <x-input-error :messages="$errors->get('pic_name')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="pic_position" :value="__('Jabatan PIC')" />
                            <x-text-input id="pic_position" class="block mt-1 w-full" type="text"
                                name="pic_position" :value="old('pic_position')"
                                placeholder="Direktur, Manajer Pengadaan, dll." />
                            <x-input-error :messages="$errors->get('pic_position')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email Perusahaan')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email"
                                name="email" :value="old('email')" required
                                placeholder="email@perusahaan.com" />
                            <p class="text-xs text-gray-400 mt-1">Digunakan untuk login & notifikasi</p>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="phone" :value="__('No. Telepon')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="tel"
                                name="phone" :value="old('phone')" required
                                placeholder="0761-xxxxxx" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Kata Sandi --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="font-bold text-sm text-[#CD422B] uppercase tracking-wider flex items-center gap-2 mb-5">
                        <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        Kata Sandi Akun
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Kata Sandi')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password"
                                name="password" required autocomplete="new-password" />
                            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>
                    </div>
                </div>

            </div>{{-- end left col --}}

            {{-- ===================== RIGHT: STICKY SIDEBAR ===================== --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-5">

                    {{-- Upload Dokumen --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="font-bold text-sm text-[#CD422B] uppercase tracking-wider flex items-center gap-2 mb-5">
                            <div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            Dokumen
                        </h2>
                        <p class="text-xs text-gray-400 mb-4">PDF / JPG / PNG · Maks. 5MB per file</p>

                        <div class="space-y-4">
                            {{-- Company Profile --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Company Profile
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <label for="company_profile"
                                    class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-[#CD422B] hover:bg-red-50/50 transition-all group">
                                    <svg class="w-6 h-6 text-gray-300 group-hover:text-[#CD422B] mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span class="text-xs text-gray-400 group-hover:text-[#CD422B] transition-colors">Klik untuk upload</span>
                                    <span id="file-company-name" class="text-xs text-green-600 font-medium mt-1 hidden block max-w-full overflow-hidden text-ellipsis whitespace-nowrap px-2"></span>
                                </label>
                                <input id="company_profile" type="file" name="company_profile"
                                    accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                    onchange="showFileName(this, 'file-company-name')" />
                                <x-input-error :messages="$errors->get('company_profile')" class="mt-1" />
                            </div>

                            {{-- SIUP --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SIUP / NIB
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <label for="siup"
                                    class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-[#CD422B] hover:bg-red-50/50 transition-all group">
                                    <svg class="w-6 h-6 text-gray-300 group-hover:text-[#CD422B] mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span class="text-xs text-gray-400 group-hover:text-[#CD422B] transition-colors">Klik untuk upload</span>
                                    <span id="file-siup-name" class="text-xs text-green-600 font-medium mt-1 hidden block max-w-full overflow-hidden text-ellipsis whitespace-nowrap px-2"></span>
                                </label>
                                <input id="siup" type="file" name="siup"
                                    accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                    onchange="showFileName(this, 'file-siup-name')" />
                                <x-input-error :messages="$errors->get('siup')" class="mt-1" />
                            </div>

                            {{-- Akta --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Akta Pendirian
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <label for="akta"
                                    class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-[#CD422B] hover:bg-red-50/50 transition-all group">
                                    <svg class="w-6 h-6 text-gray-300 group-hover:text-[#CD422B] mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span class="text-xs text-gray-400 group-hover:text-[#CD422B] transition-colors">Klik untuk upload</span>
                                    <span id="file-akta-name" class="text-xs text-green-600 font-medium mt-1 hidden block max-w-full overflow-hidden text-ellipsis whitespace-nowrap px-2"></span>
                                </label>
                                <input id="akta" type="file" name="akta"
                                    accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                    onchange="showFileName(this, 'file-akta-name')" />
                                <x-input-error :messages="$errors->get('akta')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    {{-- Terms & Submit Card --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        {{-- Agree Terms --}}
                        <div class="flex items-start gap-3 mb-5">
                            <input id="agree_terms" type="checkbox" name="agree_terms" value="1" required
                                class="mt-0.5 w-4 h-4 text-[#CD422B] border-gray-300 rounded focus:ring-[#CD422B] shrink-0">
                            <label for="agree_terms" class="text-xs text-gray-600 leading-relaxed">
                                Saya menyatakan semua data yang saya isi adalah benar dan saya menyetujui
                                <a href="#" class="text-[#CD422B] font-semibold hover:underline">Syarat & Ketentuan</a>
                                serta bersedia menandatangani Surat Perjanjian (MOU) dengan PT. Agro Pangan Maju.
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('agree_terms')" class="-mt-3 mb-3" />

                        <button type="submit" id="btn-submit-b2b"
                            class="w-full flex items-center justify-center gap-2 bg-[#CD422B] hover:bg-red-700 text-white font-bold text-sm px-6 py-3.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Pengajuan Pendaftaran
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-3">
                            Pengajuan akan ditinjau dalam 1–3 hari kerja.
                        </p>
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                        <p class="text-xs font-bold text-blue-700 mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Proses Selanjutnya
                        </p>
                        <ul class="text-xs text-blue-600 space-y-1.5">
                            <li class="flex items-start gap-1.5">
                                <span class="font-bold shrink-0">1.</span> Tim kami akan memverifikasi data perusahaan
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="font-bold shrink-0">2.</span> Notifikasi dikirim via email jika disetujui
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="font-bold shrink-0">3.</span> Anda dapat langsung login & akses harga B2B
                            </li>
                        </ul>
                    </div>

                </div>
            </div>{{-- end right sidebar --}}

        </div>{{-- end grid --}}
    </form>

    <script>
        function showFileName(input, targetId) {
            const label = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                const fullName = input.files[0].name;
                const maxLen = 25;
                let displayName = fullName;

                if (fullName.length > maxLen) {
                    const ext = fullName.lastIndexOf('.') !== -1
                        ? fullName.substring(fullName.lastIndexOf('.'))
                        : '';
                    const nameWithoutExt = fullName.substring(0, fullName.length - ext.length);
                    const charsToShow = maxLen - ext.length - 3; // 3 for "..."
                    displayName = nameWithoutExt.substring(0, charsToShow) + '...' + ext;
                }

                label.textContent = '✓ ' + displayName;
                label.classList.remove('hidden');
            }
        }
    </script>

</x-guest-b2b-layout>
