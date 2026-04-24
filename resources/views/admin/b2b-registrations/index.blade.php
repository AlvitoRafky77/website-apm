@extends('admin.layout')
@section('title', 'Registrasi B2B')
@section('subtitle', 'Kelola pengajuan mitra bisnis')

@section('content')
{{-- Stats Bar --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Semua','count'=>$counts['all'],'color'=>'gray','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Menunggu','count'=>$counts['pending'],'color'=>'amber','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Disetujui','count'=>$counts['approved'],'color'=>'green','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Ditolak','count'=>$counts['rejected'],'color'=>'red','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ] as $stat)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-11 h-11 bg-{{ $stat['color'] }}-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-{{ $stat['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $stat['count'] }}</p>
            <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan, email, PIC..."
            class="flex-1 min-w-48 border-gray-200 rounded-xl text-sm focus:border-[#CD422B] focus:ring-[#CD422B] px-4 py-2">
        <select name="status" class="border-gray-200 rounded-xl text-sm focus:border-[#CD422B] focus:ring-[#CD422B] pl-4 pr-10 py-2 transition-all appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width=%2220%22%20height=%2220%22%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%2020%2020%22%20fill=%22currentColor%22%3E%3Cpath%20fill-rule=%22evenodd%22%20d=%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule=%22evenodd%22/%3E%3C/svg%3E')] bg-no-repeat bg-[position:right_10px_center] bg-[length:1em_1em]">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="bg-[#CD422B] text-white text-sm font-semibold px-5 py-2 rounded-xl hover:bg-red-700 transition">Filter</button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.b2b.index') }}" class="text-sm text-gray-400 hover:text-gray-700 underline">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Perusahaan</th>
                    <th class="text-left px-4 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">PIC / Kontak</th>
                    <th class="text-left px-4 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelengkapan</th>
                    <th class="text-left px-4 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tgl. Daftar</th>
                    <th class="text-left px-4 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($registrations as $reg)
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $reg->company_name }}</div>
                        <div class="text-xs text-gray-400">{{ $reg->company_type }} • {{ $reg->company_city }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="font-medium text-gray-800">{{ $reg->pic_name }}</div>
                        <div class="text-xs text-gray-400">{{ $reg->email }}</div>
                        <div class="text-xs text-gray-400">{{ $reg->phone }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-1.5 min-w-16">
                                <div class="h-1.5 rounded-full {{ $reg->completeness >= 80 ? 'bg-green-500' : ($reg->completeness >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                                    style="width: {{ $reg->completeness }}%"></div>
                            </div>
                            <span class="text-xs font-bold {{ $reg->completeness >= 80 ? 'text-green-600' : ($reg->completeness >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ $reg->completeness }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        @php
                            $colors = ['pending'=>'amber','approved'=>'green','rejected'=>'red'];
                            $c = $colors[$reg->status] ?? 'gray';
                            $labels = ['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak'];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $c }}-500 {{ $reg->status==='pending' ? 'animate-pulse' : '' }}"></span>
                            {{ $labels[$reg->status] }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-xs text-gray-500">
                        {{ $reg->created_at->format('d M Y') }}<br>
                        <span class="text-gray-300">{{ $reg->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-4 py-4">
                        <a href="{{ route('admin.b2b.show', $reg) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold text-[#CD422B] hover:text-red-700 border border-[#CD422B] rounded-lg px-3 py-1.5 hover:bg-red-50 transition">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="font-medium">Belum ada pengajuan registrasi B2B</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registrations->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $registrations->links() }}
    </div>
    @endif
</div>
@endsection
