<x-app-layout>
    <div class="bg-white min-h-screen pb-20 sm:pb-8">

        <!-- Breadcrumb (Desktop) -->
        <div class="hidden sm:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm text-gray-500 font-medium">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-brand transition">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2">{{ $product->category->name ?? 'Katalog' }}</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2 text-gray-800 font-bold">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4 sm:mt-6">
            <div class="flex flex-col lg:flex-row gap-0 sm:gap-8 lg:gap-12 bg-white sm:rounded-2xl sm:shadow-lg sm:p-8 sm:border sm:border-gray-100">

                <!-- Left: Product Image -->
                <div class="w-full lg:w-5/12 shrink-0">
                    <div class="aspect-square bg-gray-50 sm:rounded-2xl overflow-hidden relative group">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    {{-- Badge Highlight --}}
                    @if($product->is_featured)
                    <div class="hidden sm:flex items-center gap-2 mt-3 justify-center text-center">
                        <span class="bg-yellow-50 text-yellow-700 text-xs font-black tracking-wide px-4 py-1.5 rounded-full border border-yellow-200 inline-flex items-center gap-1 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            PRODUK UNGGULAN
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Right: Product Info -->
                <div class="w-full lg:w-7/12 flex flex-col pt-6 px-4 sm:px-0 sm:pt-0">

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $product->name }}</h1>

                    <div class="flex items-center gap-3 mt-3 mb-5 flex-wrap">
                        <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-md border border-yellow-100">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $averageRating && $averageRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <span class="ml-1 text-xs font-black {{ $reviewCount > 0 ? 'text-yellow-700' : 'text-gray-500' }}">
                                {{ $averageRating ? number_format($averageRating, 1) : 'Belum ada rating' }}
                            </span>
                        </div>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <div class="flex items-center gap-1.5 text-sm text-gray-500">
                            <span>{{ $reviewCount }} ulasan pelanggan</span>
                        </div>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <div class="flex items-center gap-1.5 text-sm text-gray-500">
                            Kategori: <span class="bg-[#CD422B]/10 text-[#CD422B] px-2 py-0.5 rounded-md font-bold text-xs">{{ $product->category->name ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="text-3xl font-black text-[#CD422B] mb-5 border-b border-gray-100 pb-5">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                        <span class="text-base font-normal text-gray-500">/ {{ $product->unit }}</span>
                    </div>

                    <!-- Info Stok B2C -->
                    @php
                        $minOrder = max(1, (int) ($product->min_order_b2c ?? 1));
                        $maxOrder = min(
                            (int) ($product->max_order_b2c ?? $product->stock_b2c),
                            (int) $product->stock_b2c
                        );
                    @endphp
                    <div class="mb-6">
                        @if($product->stock_b2c > 0)
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-green-200 flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Stok tersedia : <strong class="font-bold text-green-800">{{ $product->stock_b2c }} KG</strong>
                                </span>
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-blue-200 shadow-sm flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Min. {{ $minOrder }} KG / transaksi
                                    </span>
                                <span class="bg-orange-50 text-orange-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-orange-200 shadow-sm flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3 3L22 4M20 14v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h9"/></svg>
                                    Maks. {{ $maxOrder }} KG / transaksi
                                </span>
                            </div>
                        @else
                            <span class="bg-red-50 text-red-700 px-4 py-2 rounded-lg text-sm font-semibold border border-red-100 inline-block">
                                ✕ Stok Habis
                            </span>
                        @endif
                    </div>

                    <!-- Deskripsi Produk -->
                    <div class="mb-6 border-b border-gray-100 pb-6">
                        <h3 class="text-base font-bold text-gray-900 mb-2">Detail Produk</h3>
                        <p class="text-gray-600 leading-relaxed text-sm lg:text-base text-justify">
                            {{ $product->full_description ?? $product->description ?? 'Deskripsi produk belum tersedia.' }}
                        </p>
                    </div>

                    <!-- ==== AREA PEMBELIAN (Desktop) ==== -->
                    <div class="hidden sm:block mt-auto pt-2 w-full">
                        @auth
                            @if(Auth::user()->isB2b())
                                {{-- B2B User — arahkan ke portal B2B --}}
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 flex items-center gap-3 mb-3">
                                    <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-gray-700 font-medium">Anda terdaftar sebagai <strong>Mitra B2B</strong>. Pemesanan partai besar melalui Dashboard khusus Anda.</p>
                                </div>
                                <a href="{{ route('b2b.dashboard') }}" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-center flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    Pesan via Portal B2B
                                </a>

                            @elseif($product->stock_b2c >= $minOrder)
                                {{-- B2C / pelanggan retail --}}
                                <div class="space-y-3">
                                    <!-- Input Kuantitas -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold text-gray-600 w-24 shrink-0">Jumlah (KG):</span>
                                        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden h-12 hover:border-[#CD422B] transition">
                                            <button type="button" onclick="changeQty(-1)"
                                                class="px-4 h-full text-gray-500 hover:text-[#CD422B] hover:bg-red-50 transition font-bold text-lg">−</button>
                                            <input type="number" id="qtyInput" value="{{ $minOrder }}" min="{{ $minOrder }}" max="{{ $maxOrder }}"
                                                oninput="validateQty(this)"
                                                style="-webkit-appearance:none;-moz-appearance:textfield;"
                                                class="w-16 h-full text-center font-bold text-gray-800 border-x border-gray-200 bg-white focus:outline-none text-base [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                            <button type="button" onclick="changeQty(1)"
                                                class="px-4 h-full text-gray-500 hover:text-[#CD422B] hover:bg-red-50 transition font-bold text-lg">+</button>
                                        </div>
                                            <span class="text-xs text-gray-400">Min. {{ $minOrder }} KG — Maks. {{ $maxOrder }} KG</span>
                                    </div>

                                    <!-- Total Harga -->
                                    <div class="bg-gray-50 rounded-xl px-4 py-2.5 flex items-center justify-between border border-gray-100">
                                        <span class="text-sm text-gray-500">Total Harga:</span>
                                        <span class="font-black text-[#CD422B] text-lg" id="totalPrice">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button type="button" id="addToCartBtn"
                                            onclick="addToCart()"
                                            class="w-full bg-gradient-to-r from-[#CD422B] to-[#b83220] hover:from-[#b83220] hover:to-[#9e2a19] text-white font-bold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Tambah ke Keranjang
                                    </button>

                                    <!-- Alt: Checkout langsung -->
                                    <a href="{{ route('checkout.index') }}"
                                       id="checkoutDirectBtn"
                                       class="w-full border-2 border-[#CD422B] text-[#CD422B] font-bold py-3 px-8 rounded-xl hover:bg-red-50 transition-all duration-200 text-center flex items-center justify-center gap-2 hidden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Lihat Keranjang & Checkout
                                    </a>
                                </div>

                            @elseif($product->stock_b2c > 0)
                                    <button disabled class="w-full bg-amber-100 text-amber-700 font-bold py-3.5 px-8 rounded-xl cursor-not-allowed text-center border border-amber-200">
                                        Stok belum memenuhi minimal order {{ $minOrder }}
                                    </button>

                            @else
                                {{-- Stok habis --}}
                                <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 px-8 rounded-xl cursor-not-allowed text-center">
                                    Stok Sedang Tidak Tersedia
                                </button>
                            @endif

                        @else
                            {{-- Guest --}}
                            <div class="flex flex-col gap-3">
                                <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-amber-700 font-medium">Silakan <strong>login</strong> terlebih dahulu untuk memesan produk ini.</p>
                                </div>
                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                                   class="w-full bg-[#CD422B] hover:bg-red-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-center">
                                    Login untuk Memesan
                                </a>
                            </div>
                        @endauth
                    </div>

                </div>
            </div>
        </div>

        <!-- Review Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8 sm:mt-10">
            <div class="bg-white sm:rounded-2xl sm:shadow-lg sm:border sm:border-gray-100 p-4 sm:p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#CD422B]/10 text-[#CD422B] text-xs font-bold px-3 py-1 mb-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Ulasan Pelanggan
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Apa kata pembeli tentang produk ini</h2>
                        <p class="text-sm text-gray-500 mt-2">Ulasan hanya bisa ditulis oleh pelanggan yang sudah menerima pesanan mereka.</p>
                    </div>

                    <div class="w-full lg:w-[340px] rounded-2xl border border-gray-100 bg-gray-50 p-5">
                        <div class="flex items-end gap-3 mb-4">
                            <span class="text-4xl font-black text-[#CD422B]">{{ $averageRating ? number_format($averageRating, 1) : '0.0' }}</span>
                            <div class="pb-1">
                                <div class="flex items-center gap-1 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $averageRating && $averageRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500">{{ $reviewCount }} ulasan terverifikasi</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @for($star = 5; $star >= 1; $star--)
                                @php
                                    $count = $ratingDistribution[$star] ?? 0;
                                    $percentage = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-10 text-xs font-bold text-gray-600">{{ $star }}★</div>
                                    <div class="flex-1 h-2.5 rounded-full bg-gray-200 overflow-hidden">
                                        <div class="h-full rounded-full bg-[#CD422B]" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="w-10 text-right text-xs text-gray-500">{{ $count }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                @if($reviewCount > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        @foreach($reviews as $review)
                            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm p-5">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-full bg-[#CD422B]/10 text-[#CD422B] font-black flex items-center justify-center text-sm shrink-0">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 text-sm truncate">{{ $review->user->name ?? 'Pelanggan APM' }}</p>
                                            <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-100 shrink-0">
                                        Pembelian Terverifikasi
                                    </span>
                                </div>

                                <div class="flex items-center gap-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    <span class="ml-1 text-xs font-bold text-gray-500">{{ number_format($review->rating, 0) }}/5</span>
                                </div>

                                @if($review->title)
                                    <h3 class="font-bold text-gray-900 text-base mb-2">{{ $review->title }}</h3>
                                @endif

                                @if($review->comment)
                                    <p class="text-sm leading-relaxed text-gray-600">{{ $review->comment }}</p>
                                @endif

                                @if($review->image_path)
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $review->image_path) }}" alt="Foto ulasan {{ $product->name }}" class="w-full h-56 object-cover rounded-2xl border border-gray-100">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.889a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.889a1 1 0 00-1.176 0l-3.976 2.889c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.616-4.674z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Belum ada ulasan untuk produk ini</h3>
                        <p class="text-sm text-gray-500 max-w-md mx-auto">Ulasan akan muncul di sini setelah pelanggan menerima barang dan membagikan pengalaman mereka.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Sticky Bottom Bar -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-lg z-50 p-4">
            @auth
                @if(Auth::user()->isB2b())
                    <a href="{{ route('b2b.dashboard') }}"
                       class="w-full bg-gray-900 text-white font-bold py-3.5 px-4 rounded-xl text-center flex items-center justify-center gap-2 transition">
                        Pesan via Portal B2B
                    </a>
                @elseif($product->stock_b2c >= $minOrder)
                    <div class="flex items-center gap-2">
                        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden h-11 shrink-0">
                            <button onclick="changeQty(-1)" class="px-2.5 h-full text-gray-500 hover:text-[#CD422B] transition font-bold">−</button>
                            <input type="number" id="qtyInputMobile" value="{{ $minOrder }}" min="{{ $minOrder }}" max="{{ $maxOrder }}"
                                   oninput="syncQty(this)"
                                   style="-webkit-appearance:none;-moz-appearance:textfield;"
                                   class="w-10 h-full text-center font-bold text-gray-800 border-x border-gray-200 bg-white focus:outline-none text-sm [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            <button onclick="changeQty(1)" class="px-2.5 h-full text-gray-500 hover:text-[#CD422B] transition font-bold">+</button>
                        </div>
                        <button type="button" onclick="addToCart()"
                           class="flex-1 bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold py-3 rounded-xl text-center flex items-center justify-center gap-1.5 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Tambah Keranjang
                        </button>
                    </div>
                    @elseif($product->stock_b2c > 0)
                    <button disabled class="w-full bg-amber-100 text-amber-700 font-bold py-3.5 rounded-xl border border-amber-200">Stok belum memenuhi minimal {{ $minOrder }}</button>
                @else
                    <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 rounded-xl">Stok Habis</button>
                @endif
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                   class="w-full bg-[#CD422B] text-white font-bold py-3.5 rounded-xl text-center block transition">
                    Login untuk Memesan
                </a>
            @endauth
        </div>
    </div>

    @push('scripts')
    <script>
        const maxOrder     = {{ $maxOrder }};
        const minOrder     = {{ $minOrder }};
        const pricePerUnit = {{ $product->price }};
        const PRODUCT_ID   = {{ $product->id }};
        const CART_ADD_URL = '{{ route('cart.add') }}';
        const CSRF_TOKEN   = '{{ csrf_token() }}';

        // ===== Quantity controls =====
        function changeQty(delta) {
            ['qtyInput', 'qtyInputMobile'].forEach(function(id) {
                var el = document.getElementById(id);
                if (!el) return;
                var val = Math.max(minOrder, Math.min(maxOrder, parseInt(el.value || minOrder) + delta));
                el.value = val;
            });
            updateTotalPrice();
        }

        function validateQty(input) {
            var val = parseInt(input.value) || minOrder;
            if (val < minOrder) { val = minOrder; showToast('Minimal pembelian ' + minOrder + ' KG per transaksi.', 'warn'); }
            if (val > maxOrder) { val = maxOrder; showToast('Maksimal ' + maxOrder + ' KG per transaksi.', 'warn'); }
            input.value = val;
            updateTotalPrice();
        }

        function updateTotalPrice() {
            var qty   = parseInt(document.getElementById('qtyInput')?.value || 1);
            var total = qty * pricePerUnit;
            var el    = document.getElementById('totalPrice');
            if (el) el.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        // ===== Add to Cart =====
        function addToCart() {
            var qty = parseInt(document.getElementById('qtyInput')?.value || 1);
            var btn = document.getElementById('addToCartBtn');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Menambahkan...';
            }

            fetch(CART_ADD_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ product_id: PRODUCT_ID, quantity: qty })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Update badge
                    if (typeof window.refreshCartBadge === 'function') window.refreshCartBadge();
                    // Show checkout button
                    var cb = document.getElementById('checkoutDirectBtn');
                    if (cb) cb.classList.remove('hidden');
                } else {
                    showToast(data.error || 'Gagal menambahkan produk.', 'error');
                }
            })
            .catch(function() { showToast('Terjadi kesalahan. Silakan coba lagi.', 'error'); })
            .finally(function() {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Tambah ke Keranjang';
                }
            });
        }

        // ===== Toast Notification =====
        function showToast(message, type) {
            var existing = document.getElementById('apmToast');
            if (existing) existing.remove();

            var colors = {
                success: 'bg-green-600',
                error:   'bg-red-600',
                warn:    'bg-amber-500'
            };
            var icons = {
                success: '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                error:   '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                warn:    '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            };

            var toast = document.createElement('div');
            toast.id = 'apmToast';
            toast.className = 'fixed bottom-6 right-6 z-[9999] flex items-center gap-3 ' + (colors[type] || 'bg-gray-800') + ' text-white text-sm font-semibold px-5 py-3.5 rounded-2xl shadow-2xl transition-all duration-300 translate-y-2 opacity-0';
            toast.style.maxWidth = '340px';
            toast.innerHTML = (icons[type] || '') + '<span>' + message + '</span>';
            document.body.appendChild(toast);

            setTimeout(function() { toast.classList.remove('translate-y-2', 'opacity-0'); }, 10);
            setTimeout(function() {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(function() { toast.remove(); }, 300);
            }, 3500);
        }

        // Init
        updateTotalPrice();
    </script>
    @endpush

</x-app-layout>
