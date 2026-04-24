<x-app-layout>
<title>Profil Saya — PT. Agro Pangan Maju</title>

<div class="min-h-screen bg-gray-50 pb-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-[#CD422B] flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=CD422B&color=fff&bold=true&size=56"
                     alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-sm text-gray-400">{{ $user->email }}</span>
                    @if($user->isB2b())
                        <span class="text-[11px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Mitra B2B</span>
                    @else
                        <span class="text-[11px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Pelanggan</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        @if(session('status') === 'profile-updated')
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl px-5 py-3.5 mb-6">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm font-semibold text-green-700">Profil berhasil diperbarui!</p>
        </div>
        @endif

        @if($user->isB2b() && $b2bData)
        {{-- =============================================
             TAMPILAN KHUSUS B2B
             ============================================= --}}

            {{-- Status Mitra --}}
            @php
                $statusCfg = match($b2bData->status) {
                    'approved' => ['bg' => 'bg-green-50',  'border' => 'border-green-200', 'icon' => 'text-green-500', 'text' => 'text-green-800',  'label' => '✅ Mitra Aktif & Disetujui'],
                    'pending'  => ['bg' => 'bg-amber-50',  'border' => 'border-amber-200', 'icon' => 'text-amber-500', 'text' => 'text-amber-800',  'label' => '⏳ Menunggu Persetujuan Admin'],
                    'rejected' => ['bg' => 'bg-red-50',    'border' => 'border-red-200',   'icon' => 'text-red-500',   'text' => 'text-red-800',    'label' => '❌ Registrasi Ditolak'],
                    default    => ['bg' => 'bg-gray-50',   'border' => 'border-gray-200',  'icon' => 'text-gray-400',  'text' => 'text-gray-700',   'label' => 'Status Tidak Diketahui'],
                };
            @endphp
            <div class="flex items-center gap-3 {{ $statusCfg['bg'] }} border {{ $statusCfg['border'] }} rounded-2xl px-5 py-4 mb-6">
                <svg class="w-5 h-5 {{ $statusCfg['icon'] }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                <div>
                    <p class="font-bold text-sm {{ $statusCfg['text'] }}">{{ $statusCfg['label'] }}</p>
                    @if($b2bData->approved_at)
                    <p class="text-xs {{ $statusCfg['text'] }} opacity-70 mt-0.5">Disetujui: {{ $b2bData->approved_at->format('d M Y') }}</p>
                    @endif
                    @if($b2bData->admin_notes)
                    <p class="text-xs {{ $statusCfg['text'] }} opacity-70 mt-0.5">Catatan admin: {{ $b2bData->admin_notes }}</p>
                    @endif
                </div>
            </div>

            {{-- Form B2B Profile --}}
            <form method="POST" action="{{ route('profile.b2b.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- SECTION: Data PIC --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Data Penanggung Jawab (PIC)
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama PIC <span class="text-red-500">*</span></label>
                            <input type="text" name="pic_name"
                                   value="{{ old('pic_name', $b2bData->pic_name) }}"
                                   required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            @error('pic_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jabatan PIC</label>
                            <input type="text" name="pic_position"
                                   value="{{ old('pic_position', $b2bData->pic_position) }}"
                                   placeholder="Contoh: Direktur, Manager Procurement"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. WhatsApp / Telepon PIC <span class="text-red-500">*</span></label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $b2bData->phone) }}"
                                   required placeholder="08xx-xxxx-xxxx"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION: Data Perusahaan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Data Perusahaan
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name"
                                   value="{{ old('company_name', $b2bData->company_name) }}"
                                   required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            @error('company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Perusahaan</label>
                            <select name="company_type"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition bg-white">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach(['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Lainnya'] as $type)
                                <option value="{{ $type }}" {{ old('company_type', $b2bData->company_type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">NPWP Perusahaan</label>
                            <input type="text" name="npwp"
                                   value="{{ old('npwp', $b2bData->npwp) }}"
                                   placeholder="XX.XXX.XXX.X-XXX.XXX"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                        </div>
                    </div>
                </div>

                {{-- SECTION: Alamat Perusahaan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Alamat Perusahaan
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Lengkap</label>
                            <textarea name="company_address" rows="2"
                                      placeholder="Jalan, Nomor, RT/RW, Kelurahan..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition resize-none">{{ old('company_address', $b2bData->company_address) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kota</label>
                                <input type="text" name="company_city"
                                       value="{{ old('company_city', $b2bData->company_city) }}"
                                       placeholder="Contoh: Pekanbaru"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Provinsi</label>
                                <input type="text" name="company_province"
                                       value="{{ old('company_province', $b2bData->company_province) }}"
                                       placeholder="Contoh: Riau"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Pos</label>
                                <input type="text" name="company_postal_code"
                                       value="{{ old('company_postal_code', $b2bData->company_postal_code) }}"
                                       placeholder="28000"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <button type="submit"
                        class="w-full bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </form>

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-5">
                <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </h2>
                @include('profile.partials.update-password-form')
            </div>

        @else
        {{-- =============================================
             TAMPILAN B2C (standar)
             ============================================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
                <h2 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Akun
                </h2>
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
                <h2 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </h2>
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                <h2 class="font-bold text-red-700 text-sm mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Akun
                </h2>
                @include('profile.partials.delete-user-form')
            </div>
        @endif

    </div>
</div>

@include('partials._footer')
</x-app-layout>
