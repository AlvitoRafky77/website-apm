@extends('admin.layout')
@section('title', 'Detail Registrasi B2B')
@section('subtitle', $b2bRegistration->company_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.b2b.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-[#CD422B] transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- LEFT: Main Info --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Company Header Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900">{{ $b2bRegistration->company_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $b2bRegistration->company_type }}
                            @if($b2bRegistration->company_city) • {{ $b2bRegistration->company_city }}, {{ $b2bRegistration->company_province }} @endif
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Daftar: {{ $b2bRegistration->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                {{-- Status Badge --}}
                @php
                    $colorMap = ['pending'=>['bg'=>'amber','label'=>'Menunggu Persetujuan'],'approved'=>['bg'=>'green','label'=>'Disetujui'],'rejected'=>['bg'=>'red','label'=>'Ditolak']];
                    $sc = $colorMap[$b2bRegistration->status] ?? ['bg'=>'gray','label'=>'Unknown'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-{{ $sc['bg'] }}-100 text-{{ $sc['bg'] }}-700">
                    <span class="w-2 h-2 rounded-full bg-{{ $sc['bg'] }}-500 {{ $b2bRegistration->status==='pending' ? 'animate-pulse' : '' }}"></span>
                    {{ $sc['label'] }}
                </span>
            </div>

            {{-- Completeness --}}
            <div class="mt-5 pt-5 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Kelengkapan Data</span>
                    <span class="text-sm font-black {{ $b2bRegistration->completeness >= 80 ? 'text-green-600' : ($b2bRegistration->completeness >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                        {{ $b2bRegistration->completeness }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $b2bRegistration->completeness >= 80 ? 'bg-green-500' : ($b2bRegistration->completeness >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                        style="width: {{ $b2bRegistration->completeness }}%"></div>
                </div>
            </div>
        </div>

        {{-- Company Details --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-[#CD422B]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                Data Perusahaan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                <div><p class="text-xs text-gray-400 mb-0.5">Jenis Perusahaan</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->company_type }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">NPWP</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->npwp ?: '-' }}</p></div>
                <div class="sm:col-span-2"><p class="text-xs text-gray-400 mb-0.5">Alamat</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->company_address }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Kota</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->company_city ?: '-' }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Provinsi</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->company_province ?: '-' }}</p></div>
            </div>
        </div>

        {{-- PIC Details --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-[#CD422B]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Person in Charge (PIC)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                <div><p class="text-xs text-gray-400 mb-0.5">Nama PIC</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->pic_name }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Jabatan</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->pic_position ?: '-' }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">Email</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->email }}</p></div>
                <div><p class="text-xs text-gray-400 mb-0.5">No. Telepon</p><p class="font-semibold text-gray-800">{{ $b2bRegistration->phone }}</p></div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-[#CD422B]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Dokumen Upload
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['label'=>'Company Profile','path'=>$b2bRegistration->company_profile_path],
                    ['label'=>'SIUP / NIB','path'=>$b2bRegistration->siup_path],
                    ['label'=>'Akta Pendirian','path'=>$b2bRegistration->akta_path],
                ] as $doc)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 {{ $doc['path'] ? 'bg-green-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $doc['path'] ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($doc['path'])
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $doc['label'] }}</p>
                            <p class="text-xs {{ $doc['path'] ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $doc['path'] ? 'Telah diupload' : 'Belum diupload' }}
                            </p>
                        </div>
                    </div>
                    @if($doc['path'])
                    <a href="{{ Storage::url($doc['path']) }}" target="_blank"
                       class="text-xs text-[#CD422B] font-semibold hover:underline flex items-center gap-1">
                        Lihat
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Admin Notes (if rejected) --}}
        @if($b2bRegistration->isRejected() && $b2bRegistration->admin_notes)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
            <h3 class="font-bold text-red-700 text-sm mb-2">Catatan Penolakan</h3>
            <p class="text-sm text-red-600">{{ $b2bRegistration->admin_notes }}</p>
        </div>
        @endif
    </div>

    {{-- RIGHT: Actions --}}
    <div class="space-y-4">
        {{-- Action Card --}}
        @if($b2bRegistration->isPending())
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Tindakan</h3>

            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.b2b.approve', $b2bRegistration) }}"
                  onsubmit="return confirm('Setujui pendaftaran {{ $b2bRegistration->company_name }}? Akun akan dibuat dan email notifikasi dikirim.')">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold text-sm px-4 py-3 rounded-xl transition mb-3 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Setujui & Aktifkan Akun
                </button>
            </form>

            {{-- Reject --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-sm px-4 py-3 rounded-xl transition border border-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Pengajuan
                </button>
                <div x-show="open" x-transition class="mt-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <form method="POST" action="{{ route('admin.b2b.reject', $b2bRegistration) }}">
                        @csrf
                        <label class="block text-xs font-bold text-red-700 mb-2">Alasan Penolakan *</label>
                        <textarea name="admin_notes" rows="3" required placeholder="Jelaskan alasan penolakan..."
                            class="w-full text-sm border-red-200 rounded-lg focus:border-red-400 focus:ring-red-400 mb-3"></textarea>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-2 rounded-lg transition">
                            Konfirmasi Penolakan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- Approved info --}}
        @if($b2bRegistration->isApproved())
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold text-green-700 text-sm">Sudah Disetujui</span>
            </div>
            <p class="text-xs text-green-600">Disetujui pada {{ $b2bRegistration->approved_at?->format('d F Y, H:i') }}</p>
            @if($b2bRegistration->approvedBy)
            <p class="text-xs text-green-500 mt-1">oleh {{ $b2bRegistration->approvedBy->name }}</p>
            @endif
        </div>
        @endif

        {{-- Download MOU --}}
        @if($b2bRegistration->isApproved())
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Dokumen MOU</h3>
            <a href="{{ route('admin.b2b.mou', $b2bRegistration) }}"
               class="w-full flex items-center justify-center gap-2 bg-[#CD422B] hover:bg-red-700 text-white font-bold text-sm px-4 py-3 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Surat Perjanjian (MOU)
            </a>
            <p class="text-xs text-gray-400 mt-2 text-center">Format PDF · Siap cetak & tanda tangan</p>
        </div>
        @endif

        {{-- Timeline / Info --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Riwayat</h3>
            <div class="space-y-3 text-xs text-gray-500">
                <div class="flex gap-2">
                    <span class="text-gray-300 shrink-0">{{ $b2bRegistration->created_at->format('d M Y') }}</span>
                    <span>Pendaftaran dikirim</span>
                </div>
                @if($b2bRegistration->approved_at)
                <div class="flex gap-2">
                    <span class="text-gray-300 shrink-0">{{ $b2bRegistration->approved_at->format('d M Y') }}</span>
                    <span>{{ $b2bRegistration->isApproved() ? 'Disetujui' : 'Ditolak' }} oleh admin</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
