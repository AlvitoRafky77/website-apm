@extends('admin.layout')
@section('title', 'Pesan Kontak')
@section('subtitle', 'Inbox pesan dari halaman contact us')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Pesan Masuk</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola pesan dari calon pelanggan dengan tampilan yang rapi dan mudah dibaca.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total</p>
                <p class="mt-1 text-xl font-black text-gray-900">{{ $totalMessages }}</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-500">Baru</p>
                <p class="mt-1 text-xl font-black text-amber-600">{{ $newMessages }}</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-sky-500">Dibaca</p>
                <p class="mt-1 text-xl font-black text-sky-600">{{ $readMessages }}</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm px-4 py-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-500">Dibalas</p>
                <p class="mt-1 text-xl font-black text-emerald-600">{{ $repliedMessages }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/60">
            <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex flex-col xl:flex-row gap-4 xl:items-center xl:justify-between">
                <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                    <div class="relative w-full sm:w-96">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, telepon, topik, pesan..."
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <select name="status" class="bg-white border border-gray-200 rounded-xl pl-4 pr-8 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 w-full sm:w-48 transition-all">
                        <option value="">Semua Status</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Baru</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Dibaca</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Dibalas</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.contact-messages.index') }}" class="text-xs text-red-500 font-medium hover:underline whitespace-nowrap">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Topik</th>
                        <th class="px-6 py-4">Pesan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($messages as $message)
                        @php
                            $statusStyle = match($message->status) {
                                'new' => 'bg-amber-100 text-amber-700',
                                'read' => 'bg-sky-100 text-sky-700',
                                'replied' => 'bg-emerald-100 text-emerald-700',
                                default => 'bg-gray-100 text-gray-700'
                            };

                            $statusLabel = match($message->status) {
                                'new' => 'Baru',
                                'read' => 'Dibaca',
                                'replied' => 'Dibalas',
                                default => ucfirst($message->status)
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <span class="text-gray-900 font-medium">{{ $message->created_at->format('d M Y, H:i') }}</span>
                                <p class="text-[11px] text-gray-400 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="space-y-1">
                                    <p class="font-bold text-gray-900">{{ $message->name }}</p>
                                    @if($message->company)
                                        <p class="text-xs text-gray-500">{{ $message->company }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">{{ $message->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->phone }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
                                    {{ $message->topic ? str_replace('_', ' ', $message->topic) : 'Umum' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <p class="text-gray-700 leading-relaxed line-clamp-3 max-w-xl">
                                    {{ \Illuminate\Support\Str::limit($message->message, 140) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusStyle }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $statusLabel }}
                                </span>
                                @if($message->read_at)
                                    <p class="text-[11px] text-gray-400 mt-1">Dibaca: {{ $message->read_at->format('d M Y, H:i') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.contact-messages.show', $message) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#CD422B] bg-red-50 hover:bg-[#CD422B] hover:text-white rounded-lg transition-colors">
                                        Lihat
                                    </a>

                                    @if($message->status !== 'replied')
                                        <form method="POST" action="{{ route('admin.contact-messages.replied', $message) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded-lg transition-colors">
                                                Tandai Dibalas
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h6m-6 8h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="font-medium text-base">Belum ada pesan masuk</p>
                                <p class="text-xs mt-1">Pesan dari halaman Contact Us akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
