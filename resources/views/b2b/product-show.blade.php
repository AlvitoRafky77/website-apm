<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} — B2B Katalog APM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f8f9fb; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp .45s ease both; }
        .fade-up-1 { animation-delay:.04s; }
        .fade-up-2 { animation-delay:.1s; }
        .fade-up-3 { animation-delay:.17s; }
    </style>
</head>
<body class="antialiased min-h-screen">

@include('partials._navbar_b2b')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#CD422B] transition font-medium">Dashboard</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-500">{{ $product->category->name ?? 'Katalog' }}</span>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-bold text-gray-700 truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-10">

        {{-- ── Gambar Produk ── --}}
        <div class="lg:col-span-2 fade-up fade-up-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden aspect-square flex items-center justify-center p-4">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-50 rounded-xl">
                        <svg class="w-24 h-24 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Badge B2B Exclusive --}}
            <div class="mt-3 flex items-center gap-2 bg-[#1a1a2e] rounded-xl px-4 py-2.5 text-white text-xs font-semibold">
                <svg class="w-4 h-4 text-[#CD422B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Produk eksklusif mitra B2B · Min. 1 Ton per order</span>
            </div>
        </div>

        {{-- ── Info Produk ── --}}
        <div class="lg:col-span-3 space-y-5 fade-up fade-up-2">

            {{-- Header --}}
            <div>
                @php
                    $catColors = [
                        'Poultry' => 'bg-amber-100 text-amber-800',
                        'Seafood' => 'bg-blue-100 text-blue-800',
                        'Meat'    => 'bg-red-100 text-red-800',
                        'Sayuran' => 'bg-green-100 text-green-800',
                        'Buah'    => 'bg-orange-100 text-orange-800',
                    ];
                    $catName  = $product->category->name ?? 'Kategori';
                    $catColor = $catColors[$catName] ?? 'bg-purple-100 text-purple-800';
                @endphp
                <span class="text-xs {{ $catColor }} px-3 py-1 rounded-full font-semibold">{{ $catName }}</span>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 mt-3 leading-tight">{{ $product->name }}</h1>
                <p class="text-gray-500 text-sm mt-2 leading-relaxed">{{ $product->description ?? 'Produk bahan baku segar berkualitas tinggi untuk kebutuhan pengadaan partai besar.' }}</p>
            </div>

            {{-- Harga --}}
            <div class="bg-gradient-to-r from-[#1a1a2e] to-[#2d1b0e] rounded-2xl p-5 text-white">
                <p class="text-xs text-white/50 font-semibold uppercase tracking-wider mb-1">Harga B2B (per KG)</p>
                <p class="text-3xl font-black text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-white/60 mt-1">Estimasi per 1 Ton: <strong class="text-[#CD422B] font-black">Rp {{ number_format($product->price * 1000, 0, ',', '.') }}</strong></p>
                <div class="mt-3 pt-3 border-t border-white/10 grid grid-cols-3 gap-3">
                    @foreach([['1 Ton', $product->price * 1000], ['5 Ton', $product->price * 5000], ['10 Ton', $product->price * 10000]] as [$label, $val])
                    <div class="bg-white/8 rounded-xl p-3 text-center">
                        <p class="text-[10px] text-white/50 mb-1">{{ $label }}</p>
                        <p class="text-sm font-black text-white">Rp {{ number_format($val, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Stok B2B --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-400 font-semibold mb-1">Stok Tersedia (B2B)</p>
                    @php $stockTon = $product->stock_b2b / 1000; @endphp
                    <p class="text-xl font-black {{ $stockTon >= 1 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ number_format($stockTon, 1, ',', '.') }} Ton
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">{{ number_format($product->stock_b2b, 0, ',', '.') }} KG tersedia</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                    <p class="text-xs text-gray-400 font-semibold mb-1">Satuan Produk</p>
                    <p class="text-xl font-black text-gray-900">{{ strtoupper($product->unit ?? 'KG') }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Min. 1 Ton · Maks. 10 Ton</p>
                </div>
            </div>

            {{-- Ketentuan B2B --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm space-y-1.5">
                <p class="font-bold text-blue-800 text-xs uppercase tracking-wide mb-2">📋 Ketentuan Pengadaan B2B</p>
                @foreach([
                    'Minimum pembelian 1 Ton (1.000 KG) per transaksi',
                    'Maksimum pembelian 10 Ton (10.000 KG) per transaksi',
                    'Pembayaran DP 50% di awal untuk konfirmasi order',
                    'Pelunasan 50% sebelum barang diturunkan dari armada',
                    'Bukti transfer wajib diupload melalui portal ini',
                    'Harga belum termasuk ongkos kirim armada APM',
                ] as $item)
                <div class="flex items-start gap-2">
                    <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-blue-700 text-xs">{{ $item }}</span>
                </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="flex gap-3">
                <a href="{{ route('b2b.orders.create') }}"
                   class="flex-1 bg-[#CD422B] hover:bg-red-700 text-white font-bold py-3.5 rounded-xl text-sm text-center transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Order Sekarang
                </a>
                <a href="https://wa.me/6281234567890?text=Halo+Admin+APM,+saya+ingin+tanya+tentang+produk+{{ urlencode($product->name) }}" target="_blank"
                   class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-3.5 rounded-xl text-sm transition">
                    <svg class="w-4 h-4" viewBox="0 0 32 32" fill="currentColor"><path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.737 5.469 2.027 7.773L0 32l8.479-2.003A15.926 15.926 0 0016 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 01-6.773-1.853l-.487-.288-4.987 1.179 1.23-4.847-.32-.499A13.27 13.27 0 012.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.405-9.867c-.405-.203-2.4-1.184-2.771-1.32-.373-.136-.645-.203-.917.203-.272.405-1.048 1.32-1.285 1.593-.237.271-.475.305-.88.101-.405-.203-1.711-.63-3.26-2.013-1.204-1.073-2.018-2.4-2.254-2.805-.237-.405-.025-.624.178-.827.181-.181.405-.473.608-.71.203-.237.271-.405.405-.676.136-.272.068-.508-.033-.71-.101-.203-.917-2.207-1.255-3.023-.329-.796-.665-.688-.917-.7-.237-.012-.508-.015-.78-.015s-.71.101-1.084.508c-.372.405-1.421 1.389-1.421 3.389s1.455 3.931 1.658 4.203c.203.272 2.865 4.372 6.94 6.128.968.419 1.724.668 2.313.856.972.307 1.857.264 2.557.16.78-.117 2.4-.981 2.74-1.929.337-.947.337-1.759.236-1.929-.1-.169-.372-.271-.78-.473z"/></svg>
                    Tanya
                </a>
            </div>
        </div>
    </div>

    {{-- ── Deskripsi Lengkap ── --}}
    @if($product->full_description)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8 fade-up fade-up-3">
        <h2 class="font-bold text-gray-900 mb-3 text-sm">Deskripsi Produk</h2>
        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed text-sm">
            {!! nl2br(e($product->full_description)) !!}
        </div>
    </div>
    @endif

    {{-- ── Produk Serupa ── --}}
    @if($related->isNotEmpty())
    <div class="fade-up fade-up-3">
        <h2 class="font-bold text-gray-900 mb-4">Produk Serupa di Kategori Ini</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($related as $r)
            <a href="{{ route('b2b.products.show', $r->slug) }}"
               class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all overflow-hidden group">
                <div class="aspect-square overflow-hidden bg-gray-50">
                    @if($r->image)
                        <img src="{{ $r->image }}" alt="{{ $r->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-200 text-4xl">📦</div>
                    @endif
                </div>
                <div class="p-3">
                    <p class="font-bold text-gray-900 text-xs leading-tight line-clamp-2">{{ $r->name }}</p>
                    <p class="text-[#CD422B] font-black text-sm mt-1">Rp {{ number_format($r->price, 0, ',', '.') }}<span class="text-gray-400 font-medium text-[10px]">/KG</span></p>
                    @php $rTon = $r->stock_b2b / 1000; @endphp
                    <p class="text-[10px] text-gray-400 mt-0.5">Stok: {{ number_format($rTon, 1, ',', '.') }} Ton</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

@include('partials._footer')
</body>
</html>
