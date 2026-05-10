@extends('admin.layout')
@section('title', 'Tarif Ongkir')
@section('subtitle', 'Kelola tarif pengiriman per kota tujuan — berdasarkan jarak & berat')

@section('content')

{{-- Form Tambah Tarif --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h3 class="font-bold text-gray-900 text-sm mb-4">➕ Tambah Tarif Baru</h3>
    <form method="POST" action="{{ route('admin.shipping-rates.store') }}">
        @csrf
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
            <div>
                <label class="text-xs font-semibold text-gray-600 block mb-1">Kota Tujuan</label>
                <input type="text" name="destination_city" required placeholder="Contoh: Padang"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:outline-none">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 block mb-1">Tarif per KG (Rp)</label>
                <input type="number" name="rate_per_kg" required min="0" step="1" placeholder="500"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:outline-none">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 block mb-1">Min. Berat (KG)</label>
                <input type="number" name="min_weight_kg" value="1000" min="0" step="100"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:outline-none">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 block mb-1">Estimasi (Hari)</label>
                <input type="number" name="estimated_days" min="1" max="30" placeholder="2"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#CD422B] hover:bg-red-700 text-white font-bold py-2.5 rounded-lg transition text-sm">
                    Simpan
                </button>
            </div>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-600 block mb-1">Catatan (Opsional)</label>
            <input type="text" name="notes" placeholder="Contoh: Via jalur darat, 1-2 hari kerja"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:outline-none">
        </div>
    </form>
</div>

{{-- Tabel Tarif --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                    <th class="px-6 py-4">Rute</th>
                    <th class="px-6 py-4">Tarif/KG</th>
                    <th class="px-6 py-4">Min. Berat</th>
                    <th class="px-6 py-4">Est. Hari</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Simulasi (1 Ton)</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rates as $rate)
                <tr class="hover:bg-gray-50/60 transition group" id="rate-{{ $rate->id }}">
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900 text-sm">{{ $rate->origin_city }} → {{ $rate->destination_city }}</p>
                        @if($rate->notes)
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $rate->notes }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-[#CD422B] text-sm">Rp {{ number_format($rate->rate_per_kg, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ number_format($rate->min_weight_kg, 0, ',', '.') }} KG
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $rate->estimated_days ? $rate->estimated_days . ' hari' : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($rate->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800 text-sm">Rp {{ number_format($rate->rate_per_kg * 1000, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center gap-2 justify-end">
                            <button onclick="document.getElementById('edit-{{ $rate->id }}').classList.toggle('hidden')"
                                    class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-700 transition">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.shipping-rates.destroy', $rate) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus tarif ke {{ $rate->destination_city }}?')"
                                        class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- Inline Edit Row --}}
                <tr id="edit-{{ $rate->id }}" class="hidden bg-amber-50/50">
                    <td colspan="7" class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.shipping-rates.update', $rate) }}">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 items-end">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Kota Tujuan</label>
                                    <input type="text" name="destination_city" value="{{ $rate->destination_city }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Tarif/KG (Rp)</label>
                                    <input type="number" name="rate_per_kg" value="{{ (int) $rate->rate_per_kg }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Min. Berat (KG)</label>
                                    <input type="number" name="min_weight_kg" value="{{ (int) $rate->min_weight_kg }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Est. Hari</label>
                                    <input type="number" name="estimated_days" value="{{ $rate->estimated_days }}"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Status</label>
                                    <select name="is_active" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                        <option value="1" {{ $rate->is_active ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !$rate->is_active ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-lg transition text-sm">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <p class="font-semibold mb-1">Belum ada tarif ongkir</p>
                        <p class="text-xs">Tambahkan tarif di form atas untuk memulai.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
