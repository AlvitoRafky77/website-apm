@extends('admin.layout')
@section('title', 'Detail Pesan Kontak')
@section('subtitle', 'Lihat isi pesan dan tindak lanjut')

@section('content')
@php
    $statusStyle = match($contactMessage->status) {
        'new' => 'bg-amber-100 text-amber-700',
        'read' => 'bg-sky-100 text-sky-700',
        'replied' => 'bg-emerald-100 text-emerald-700',
        default => 'bg-gray-100 text-gray-700'
    };

    $statusLabel = match($contactMessage->status) {
        'new' => 'Baru',
        'read' => 'Dibaca',
        'replied' => 'Dibalas',
        default => ucfirst($contactMessage->status)
    };

    $mailSubject = rawurlencode('Re: Pesan Contact Us - ' . ($contactMessage->name ?? 'Customer'));
    $mailBody = rawurlencode("Halo {$contactMessage->name},\n\nTerima kasih sudah menghubungi PT. Agro Pangan Maju.\n\nTerkait pesan Anda:\n{$contactMessage->message}\n\nSalam,\nPT. Agro Pangan Maju");
    $waMessage = rawurlencode("Halo {$contactMessage->name}, terima kasih sudah menghubungi PT. Agro Pangan Maju. Kami ingin menindaklanjuti pesan Anda.");
@endphp

<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-black text-gray-900">Detail Pesan</h2>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusStyle }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $statusLabel }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Diterima {{ $contactMessage->created_at->format('d M Y, H:i') }}
                @if($contactMessage->read_at)
                    · Dibaca {{ $contactMessage->read_at->format('d M Y, H:i') }}
                @endif
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="mailto:{{ $contactMessage->email }}?subject={{ $mailSubject }}&body={{ $mailBody }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#CD422B] text-white font-semibold text-sm hover:bg-[#b93a26] transition-colors">
                Balas Email
            </a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactMessage->phone) }}?text={{ $waMessage }}"
               target="_blank" rel="noopener"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 text-white font-semibold text-sm hover:bg-emerald-600 transition-colors">
                Balas WhatsApp
            </a>
            @if($contactMessage->status !== 'replied')
                <form method="POST" action="{{ route('admin.contact-messages.replied', $contactMessage) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors">
                        Tandai Dibalas
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.contact-messages.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Isi Pesan</p>
                        <h3 class="mt-1 text-xl font-black text-gray-900">{{ $contactMessage->topic ? str_replace('_', ' ', $contactMessage->topic) : 'Umum' }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Status</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $statusLabel }}</p>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5">
                    <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">{{ $contactMessage->message }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Riwayat & Tindakan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 text-[#CD422B] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 2.761-4.03 5-9 5a17.3 17.3 0 01-4-.455L3 17l1.455-4A7.986 7.986 0 013 12c0-2.761 4.03-5 9-5s9 2.239 9 5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Pesan masuk dari contact form</p>
                            <p class="text-gray-500 text-xs">{{ $contactMessage->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Status saat ini</p>
                            <p class="text-gray-500 text-xs">{{ $statusLabel }}</p>
                        </div>
                    </div>

                    @if($contactMessage->read_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Dibaca admin</p>
                                <p class="text-gray-500 text-xs">{{ $contactMessage->read_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Informasi Pengirim</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Nama</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $contactMessage->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Perusahaan</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $contactMessage->company ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Email</p>
                        <a href="mailto:{{ $contactMessage->email }}" class="mt-1 inline-block font-semibold text-[#CD422B] hover:underline">{{ $contactMessage->email }}</a>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Telepon</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactMessage->phone) }}" target="_blank" rel="noopener" class="mt-1 inline-block font-semibold text-[#CD422B] hover:underline">{{ $contactMessage->phone }}</a>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Topik</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $contactMessage->topic ? str_replace('_', ' ', $contactMessage->topic) : 'Umum' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Catatan Admin</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Gunakan tombol balas email atau WhatsApp di atas untuk follow-up cepat. Jika sudah ditangani, tandai pesan sebagai <span class="font-semibold">dibalas</span>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
