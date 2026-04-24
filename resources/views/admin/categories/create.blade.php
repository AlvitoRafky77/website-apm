@extends('admin.layout')
@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition"
                    placeholder="Contoh: Seafood, Poultry, Meat">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-400 mt-2">Slug akan dibuat otomatis dari nama kategori.</p>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-[#CD422B] text-white font-semibold text-sm px-8 py-3 rounded-xl hover:bg-red-700 transition">Simpan</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 text-gray-700 font-semibold text-sm px-8 py-3 rounded-xl hover:bg-gray-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
