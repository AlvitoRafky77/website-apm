<x-app-layout>
<title>Detail Pesanan {{ $order->order_id }} — PT. Agro Pangan Maju</title>

<div class="min-h-screen bg-gray-50 pb-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('orders.index') }}" class="hover:text-[#CD422B] transition font-medium">Pesanan Saya</a>
            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-bold text-gray-700">{{ $order->order_id }}</span>
        </nav>

        @php
            $statusTimeline = [
                ['key' => 'pending',    'label' => 'Pesanan Dibuat',     'icon' => '🛒'],
                ['key' => 'confirmed',  'label' => 'Dikonfirmasi',        'icon' => '✅'],
                ['key' => 'processing', 'label' => 'Sedang Diproses',     'icon' => '📦'],
                ['key' => 'shipped',    'label' => 'Sedang Dikirim',      'icon' => '🚚'],
                ['key' => 'delivered',  'label' => 'Selesai',             'icon' => '🎉'],
            ];
            $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
            $currentIdx  = array_search($order->status, $statusOrder) ?? 0;
            if ($order->status === 'cancelled') $currentIdx = -1;

            $items = $reviewableItems ?? (is_array($order->items) ? $order->items : json_decode($order->items, true));
        @endphp

        {{-- ===== TIMELINE STATUS (like Tokopedia) ===== --}}
        @if($order->status !== 'cancelled')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="font-bold text-gray-800 text-sm mb-5">Status Pesanan</h2>
            <div class="relative flex items-start justify-between">
                {{-- Progress bar --}}
                <div class="absolute top-4 left-0 right-0 h-1 bg-gray-100 z-0">
                    <div class="h-full bg-[#CD422B] transition-all duration-700 rounded-full"
                         style="width: {{ $currentIdx >= 0 ? ($currentIdx / (count($statusTimeline) - 1)) * 100 : 0 }}%"></div>
                </div>

                @foreach($statusTimeline as $idx => $step)
                @php $done = $idx <= $currentIdx; @endphp
                <div class="relative z-10 flex flex-col items-center gap-2 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm transition-all duration-300
                                {{ $done ? 'bg-[#CD422B] shadow-md shadow-red-200' : 'bg-gray-100 text-gray-400' }}">
                        @if($done)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="text-xs">{{ $idx + 1 }}</span>
                        @endif
                    </div>
                    <p class="text-[10px] font-semibold text-center leading-tight {{ $done ? 'text-[#CD422B]' : 'text-gray-400' }}">
                        {{ $step['label'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-100 rounded-2xl px-5 py-4 flex items-center gap-3 mb-6">
            <span class="text-xl">❌</span>
            <div>
                <p class="font-bold text-red-700 text-sm">Pesanan Dibatalkan</p>
                <p class="text-xs text-red-500">Pesanan ini telah dibatalkan.</p>
            </div>
        </div>
        @endif

        {{-- ===== INFO PESANAN ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Info Pesanan
                </h2>
                <span class="font-mono text-xs text-gray-400">{{ $order->order_id }}</span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal Pesanan</span>
                    <span class="font-semibold text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status Pembayaran</span>
                    @php
                        $payBadge = match($order->payment_status) {
                            'paid'    => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            default   => 'bg-red-100 text-red-700',
                        };
                        $payLabel = match($order->payment_status) {
                            'paid'    => 'Sudah Dibayar',
                            'pending' => 'Belum Dibayar',
                            'expired' => 'Kedaluwarsa',
                            default   => 'Gagal',
                        };
                    @endphp
                    <span class="text-xs font-bold px-3 py-0.5 rounded-full {{ $payBadge }}">{{ $payLabel }}</span>
                </div>
                @if($order->midtrans_payment_type)
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode Bayar</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ str_replace('_', ' ', $order->midtrans_payment_type) }}</span>
                </div>
                @endif
                @if($order->paid_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Waktu Bayar</span>
                    <span class="font-semibold text-gray-800">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== DAFTAR PRODUK ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Produk Dipesan ({{ count($items) }})
            </h2>
            <div class="divide-y divide-gray-50">
                @foreach($items as $item)
                <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                    <div class="w-14 h-14 rounded-xl bg-gray-100 border border-gray-100 overflow-hidden flex-shrink-0">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm">{{ $item['name'] }}</p>
                        @php
                            $wpu = $item['weight'] ?? 1;
                            $totalKg = $item['quantity'] * $wpu;
                        @endphp
                        <p class="text-xs text-gray-400 mt-0.5">{{ $totalKg }} Kg × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== ALAMAT PENGIRIMAN ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Alamat Pengiriman
            </h2>
            <div class="space-y-1.5 text-sm">
                <p class="font-bold text-gray-900">{{ $order->recipient_name }}</p>
                <p class="text-gray-600">📱 {{ $order->recipient_phone }}</p>
                <p class="text-gray-600">📍 {{ $order->recipient_address }}, {{ $order->recipient_city }}</p>
                @if($order->notes)
                <p class="text-gray-500 text-xs mt-2 bg-gray-50 rounded-lg px-3 py-2">
                    📝 <em>{{ $order->notes }}</em>
                </p>
                @endif
            </div>
        </div>

        {{-- ===== ULASAN PRODUK ===== --}}
        @if($canReview)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div>
                    <h2 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.889a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.889a1 1 0 00-1.176 0l-3.976 2.889c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.616-4.674z"/></svg>
                        Ulasan Produk
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Tulis pengalaman Anda setelah pesanan benar-benar diterima.</p>
                </div>

                <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">Bisa Diulas</span>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 font-medium">
                    {{ session('info') }}
                </div>
            @endif

            <div class="space-y-4">
                @foreach($items as $item)
                    @php
                        $productId = (int) ($item['product_id'] ?? 0);
                        $isReviewed = (bool) ($item['is_reviewed'] ?? false);
                    @endphp

                    <div class="rounded-2xl border border-gray-100 bg-gray-50/70 overflow-hidden">
                        <div class="flex items-center gap-4 p-4">
                            <div class="w-14 h-14 rounded-xl bg-white border border-gray-100 overflow-hidden flex-shrink-0">
                                @if(!empty($item['image']))
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">Bagikan kualitas produk, pengemasan, dan pengalaman pengiriman Anda.</p>
                            </div>

                            @if($isReviewed)
                                <span class="hidden sm:inline-flex text-[11px] font-bold px-3 py-1 rounded-full bg-green-100 text-green-700">Sudah Diulas</span>
                            @endif
                        </div>

                        @if(!$isReviewed)
                            <form method="POST" action="{{ route('orders.reviews.store', $order->order_id) }}" enctype="multipart/form-data" class="border-t border-gray-100 bg-white p-4 sm:p-5 space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Rating</label>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <div class="review-star-rating flex items-center gap-2" data-initial="{{ (string) old('product_id') === (string) $productId ? (int) old('rating', 0) : 0 }}">
                                            <input type="hidden" name="rating" value="{{ (string) old('product_id') === (string) $productId ? (int) old('rating', 0) : 0 }}">
                                            @for($star = 1; $star <= 5; $star++)
                                                <button type="button"
                                                        class="review-star-btn inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 bg-white text-gray-300 transition"
                                                        data-value="{{ $star }}"
                                                        aria-label="Pilih {{ $star }} bintang">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-400">Pilih 1–5 bintang</span>
                                    </div>
                                    @if((string) old('product_id') === (string) $productId && $errors->has('rating'))
                                        <p class="text-xs text-red-500 mt-2">{{ $errors->first('rating') }}</p>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-2">Judul Ulasan</label>
                                        <input type="text"
                                               name="title"
                                               maxlength="120"
                                               value="{{ (string) old('product_id') === (string) $productId ? old('title') : '' }}"
                                               placeholder="Contoh: Kualitas segar dan pengiriman cepat"
                                               class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
                                        @if((string) old('product_id') === (string) $productId && $errors->has('title'))
                                            <p class="text-xs text-red-500 mt-2">{{ $errors->first('title') }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-2">Foto Produk <span class="text-gray-400 font-medium">(opsional)</span></label>
                                        <input type="file"
                                               name="image"
                                               accept="image/*"
                                               class="block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-[#CD422B] file:px-3 file:py-2 file:text-white file:text-xs file:font-bold hover:file:bg-red-700">
                                        @if((string) old('product_id') === (string) $productId && $errors->has('image'))
                                            <p class="text-xs text-red-500 mt-2">{{ $errors->first('image') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Komentar</label>
                                    <textarea name="comment"
                                              rows="4"
                                              maxlength="1200"
                                              placeholder="Ceritakan kualitas produk, kondisi saat diterima, dan apakah Anda ingin membeli lagi."
                                              class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">{{ (string) old('product_id') === (string) $productId ? old('comment') : '' }}</textarea>
                                    @if((string) old('product_id') === (string) $productId && $errors->has('comment'))
                                        <p class="text-xs text-red-500 mt-2">{{ $errors->first('comment') }}</p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <p class="text-xs text-gray-400">Ulasan akan langsung tampil di halaman produk setelah dikirim.</p>
                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#CD422B] hover:bg-red-700 text-white font-bold text-sm px-5 py-3 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.976 2.889a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.889a1 1 0 00-1.176 0l-3.976 2.889c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.616-4.674z"/></svg>
                                        Kirim Ulasan
                                    </button>
                                </div>
                            </form>
                        @elseif($isReviewed)
                            <div class="border-t border-gray-100 bg-green-50 px-4 py-3 text-sm text-green-700 font-medium flex items-center gap-2">
                                <span>✅</span>
                                <span>Anda sudah memberikan ulasan untuk produk ini pada pesanan ini.</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ===== RINGKASAN PEMBAYARAN ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Ringkasan Pembayaran
            </h2>
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal ({{ count($items) }} produk)</span>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ongkos Kirim</span>
                    @if($order->shipping_cost > 0)
                        <span class="font-semibold text-gray-700">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    @else
                        <span class="text-amber-600 font-semibold text-xs bg-amber-50 px-2 py-0.5 rounded-full">Akan dikonfirmasi</span>
                    @endif
                </div>
                <hr class="border-gray-100">
                <div class="flex justify-between">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="font-black text-lg text-[#CD422B]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- ===== ACTION BUTTONS ===== --}}
        <div class="flex flex-col gap-3">
            {{-- Konfirmasi Diterima & Bantuan Komplain (untuk order yang shipped / delivered) --}}
            @if(in_array($order->status, ['shipped', 'delivered']) && $order->payment_status === 'paid')
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    @if(!$order->user_confirmed && $order->status === 'shipped')
                    <form method="POST" action="{{ route('orders.confirm', $order->order_id) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Konfirmasi bahwa pesanan Anda sudah diterima?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition shadow-md flex items-center justify-center gap-2 text-sm">
                            ✅ Pesanan Diterima
                        </button>
                    </form>
                    @else
                    <div class="flex-1 bg-green-50 border border-green-200 text-green-700 font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 text-sm text-center">
                        ✅ Penerimaan Dikonfirmasi
                    </div>
                    @endif

                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20CS%20APM%2C%20saya%20ingin%20melaporkan%20kendala%2Fkomplain%20pada%20Pesanan%20%23{{ $order->order_id }}"
                       target="_blank"
                       class="flex-1 bg-amber-50 border border-amber-300 text-amber-800 hover:bg-amber-100 font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 text-sm text-center">
                        ⚠️ Laporkan Kendala / Komplain
                    </a>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Lanjutkan Bayar --}}
                @if($order->payment_status === 'pending' && $order->midtrans_snap_token)
                <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                        data-client-key="{{ config('midtrans.client_key') }}"></script>
                <button onclick="snap.pay('{{ $order->midtrans_snap_token }}', {
                            onSuccess: function() { window.location.href='{{ route('checkout.finish') }}?order_id={{ $order->order_id }}'; },
                            onPending: function() { window.location.reload(); }
                        })"
                        class="flex-1 bg-[#CD422B] text-white font-bold py-3.5 rounded-xl hover:bg-red-700 transition shadow-md flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Bayar Sekarang
                </button>
                @endif


            {{-- Download Invoice --}}
            @if($order->payment_status === 'paid')
            <a href="{{ route('orders.invoice', $order->order_id) }}"
               class="flex-1 bg-white border-2 border-[#CD422B] text-[#CD422B] font-bold py-3.5 rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Invoice
            </a>
            @endif

            {{-- Kembali --}}
            <a href="{{ route('orders.index') }}"
               class="flex-1 bg-white border-2 border-gray-200 text-gray-700 font-bold py-3.5 rounded-xl hover:border-gray-300 transition flex items-center justify-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Pesanan Saya
            </a>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.review-star-rating').forEach(function (ratingEl) {
        var input = ratingEl.querySelector('input[name="rating"]');
        var buttons = Array.from(ratingEl.querySelectorAll('.review-star-btn'));

        function paint(activeValue) {
            buttons.forEach(function (button) {
                var value = Number(button.dataset.value || 0);
                var isActive = value <= activeValue;

                button.classList.toggle('bg-yellow-50', isActive);
                button.classList.toggle('border-yellow-300', isActive);
                button.classList.toggle('text-yellow-500', isActive);

                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('border-gray-200', !isActive);
                button.classList.toggle('text-gray-300', !isActive);
            });
        }

        buttons.forEach(function (button) {
            button.addEventListener('mouseenter', function () {
                paint(Number(button.dataset.value || 0));
            });

            button.addEventListener('click', function () {
                var selected = Number(button.dataset.value || 0);
                input.value = selected;
                paint(selected);
            });
        });

        ratingEl.addEventListener('mouseleave', function () {
            paint(Number(input.value || ratingEl.dataset.initial || 0));
        });

        paint(Number(input.value || ratingEl.dataset.initial || 0));
    });
});
</script>

@include('partials._footer')
</x-app-layout>
