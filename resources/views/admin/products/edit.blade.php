@extends('admin.layout')
@section('title', 'Edit Produk')
@section('subtitle', 'Perbarui informasi produk')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">

            <!-- Nama Produk -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Kategori & Satuan -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition"
                        placeholder="Contoh: 1 Kg, 500 Gram">
                    @error('unit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="500"
                        class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                </div>
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- ===== MANAJEMEN STOK (Live Update) ===== -->
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    <h4 class="text-sm font-bold text-gray-700">Manajemen Stok</h4>
                    <span class="ml-auto text-[10px] bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full animate-pulse">● Live Update</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Stok B2C -->
                    <div class="bg-white rounded-xl p-4 border border-blue-100 shadow-sm">
                        <label class="block text-xs font-bold text-blue-600 mb-1 uppercase tracking-wide">Stok B2C</label>
                        <p class="text-[10px] text-gray-400 mb-2">Retail / Pelanggan Umum</p>
                        <input type="number" name="stock_b2c" id="stock_b2c"
                            value="{{ old('stock_b2c', $product->stock_b2c) }}" required min="0"
                            oninput="document.getElementById('previewB2c').textContent = this.value || 0"
                            class="w-full border border-blue-200 rounded-lg px-3 py-2 text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                            placeholder="0">
                        @error('stock_b2c')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Stok B2B -->
                    <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
                        <label class="block text-xs font-bold text-emerald-600 mb-1 uppercase tracking-wide">Stok B2B</label>
                        <p class="text-[10px] text-gray-400 mb-2">Mitra / Partai Besar</p>
                        <input type="number" name="stock_b2b" id="stock_b2b"
                            value="{{ old('stock_b2b', $product->stock_b2b) }}" required min="0"
                            oninput="document.getElementById('previewB2b').textContent = this.value || 0"
                            class="w-full border border-emerald-200 rounded-lg px-3 py-2 text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400 transition"
                            placeholder="0">
                        @error('stock_b2b')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>

                <!-- Live Preview Bar -->
                <div class="bg-white rounded-xl px-4 py-3 border border-gray-100 flex items-center gap-6 text-sm flex-wrap">
                    <span class="text-gray-400 text-xs font-medium">Preview saat ini:</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="text-xs text-gray-600">Stok B2C: <strong class="text-blue-600" id="previewB2c">{{ $product->stock_b2c }}</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-xs text-gray-600">Stok B2B: <strong class="text-emerald-600" id="previewB2b">{{ $product->stock_b2b }}</strong></span>
                    </div>
                </div>

                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Stok B2C dan B2B dikelola terpisah. Di website retail, aturan order otomatis: minimal 10 KG, maksimal 200 KG, dan tetap mengikuti stok yang tersedia.
                </p>
            </div>

            <!-- Berat Produk (untuk kalkulasi ongkir Lalamove) -->
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    <h4 class="text-sm font-bold text-gray-700">Berat Produk</h4>
                    <span class="text-[10px] bg-indigo-100 text-indigo-600 font-bold px-2 py-0.5 rounded-full ml-1">Untuk Ongkir Lalamove</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Berat Per Unit (KG) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 1) }}" required min="0.01" step="0.01"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition"
                                placeholder="1.00">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-semibold">KG</span>
                        </div>
                        @error('weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end pb-1">
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Berat ini digunakan untuk menentukan jenis kendaraan Lalamove secara otomatis saat checkout.
                            <br><span class="text-indigo-500 font-medium">Contoh: 1 KG = 1 unit produk seberat 1 kilogram.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition resize-none">{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Gambar -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                @if($product->image)
                <div class="mb-3 flex items-center gap-4">
                    <img src="{{ $product->image }}" class="h-24 w-24 object-cover rounded-xl border border-gray-200" alt="Gambar saat ini">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Gambar saat ini</p>
                        <p class="text-xs text-gray-400 mt-0.5">Upload gambar baru untuk mengganti</p>
                    </div>
                </div>
                @endif
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-[#CD422B]/40 transition cursor-pointer" onclick="document.getElementById('imageInput').click()">
                    <div id="previewContainer" class="hidden mb-4">
                        <img id="imagePreview" class="h-32 mx-auto rounded-lg object-cover" src="" alt="Preview">
                    </div>
                    <div id="uploadPlaceholder">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-gray-500">Klik untuk ganti gambar</p>
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                </div>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Toggle: Featured & Aktif -->
            <div class="flex items-center gap-8 pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                        class="w-5 h-5 rounded accent-[#CD422B]">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">Produk Unggulan</p>
                        <p class="text-xs text-gray-400">Tampil di katalog bagian atas</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                        class="w-5 h-5 rounded accent-[#CD422B]">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">Produk Aktif</p>
                        <p class="text-xs text-gray-400">Tampil di halaman publik</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-[#CD422B] text-white font-semibold text-sm px-8 py-3 rounded-xl hover:bg-red-700 transition shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-100 text-gray-700 font-semibold text-sm px-8 py-3 rounded-xl hover:bg-gray-200 transition">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadPlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
