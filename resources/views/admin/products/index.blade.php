@extends('admin.layout')
@section('title', 'Manajemen Produk')
@section('subtitle', 'Daftar semua produk')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-[#CD422B] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-red-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        Tambah Produk
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="text-left px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="text-left px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-blue-500 uppercase tracking-wider">Stok B2C</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-emerald-500 uppercase tracking-wider">Stok B2B</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition-colors">
                <!-- Gambar + Nama -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-100">
                            @if($product->image)
                                <img src="{{ $product->image }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $product->unit }}</p>
                        </div>
                    </div>
                </td>
                <!-- Kategori -->
                <td class="px-4 py-4">
                    @if($product->category)
                        <span class="bg-orange-50 text-orange-700 font-medium text-xs px-3 py-1.5 rounded-full">{{ $product->category->name }}</span>
                    @else
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
                <!-- Harga -->
                <td class="px-4 py-4">
                    <p class="font-bold text-[#CD422B]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </td>
                <!-- Stok B2C -->
                <td class="px-4 py-4 text-center">
                    @php $b2c = $product->stock_b2c; @endphp
                    @if($b2c <= 0)
                        <span class="inline-block bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-red-100">Habis</span>
                    @elseif($b2c <= 5)
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="inline-block bg-amber-50 text-amber-600 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-amber-100">{{ $b2c }}</span>
                            <span class="text-[9px] text-amber-500 font-medium">⚠ Hampir Abis</span>
                        </div>
                    @else
                        <span class="inline-block bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-blue-100">{{ $b2c }}</span>
                    @endif
                </td>
                <!-- Stok B2B -->
                <td class="px-4 py-4 text-center">
                    @php $b2b = $product->stock_b2b; @endphp
                    @if($b2b <= 0)
                        <span class="inline-block bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-red-100">Habis</span>
                    @elseif($b2b <= 5)
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="inline-block bg-amber-50 text-amber-600 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-amber-100">{{ $b2b }}</span>
                            <span class="text-[9px] text-amber-500 font-medium">⚠ Hampir Abis</span>
                        </div>
                    @else
                        <span class="inline-block bg-emerald-50 text-emerald-700 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-emerald-100">{{ $b2b }}</span>
                    @endif
                </td>
                <!-- Status -->
                <td class="px-4 py-4 text-center">
                    @if($product->is_active)
                        <span class="bg-green-50 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">Aktif</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-3 py-1.5 rounded-full">Tidak Aktif</span>
                    @endif
                </td>
                <!-- Aksi -->
                <td class="px-4 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 transition px-3 py-2 rounded-lg text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 transition px-3 py-2 rounded-lg text-xs font-semibold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <p class="font-medium">Belum ada produk.</p>
                    <a href="{{ route('admin.products.create') }}" class="text-[#CD422B] font-semibold text-sm mt-2 inline-block hover:underline">Tambah produk pertama →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
