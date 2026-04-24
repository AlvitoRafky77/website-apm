@extends('admin.layout')
@section('title', 'Manajemen Kategori')
@section('subtitle', 'Daftar kategori produk')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 bg-[#CD422B] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-red-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                <th class="text-left px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-semibold text-gray-900">{{ $category->name }}</td>
                <td class="px-4 py-4"><code class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-lg">{{ $category->slug }}</code></td>
                <td class="px-4 py-4 text-center">
                    <span class="bg-red-50 text-[#CD422B] font-bold text-sm px-4 py-1.5 rounded-full">{{ $category->products_count }}</span>
                </td>
                <td class="px-4 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 transition px-3 py-2 rounded-lg text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 transition px-3 py-2 rounded-lg text-xs font-semibold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-16 text-gray-400">
                    <p class="font-medium">Belum ada kategori.</p>
                    <a href="{{ route('admin.categories.create') }}" class="text-[#CD422B] font-semibold text-sm mt-2 inline-block hover:underline">Tambah kategori pertama →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
