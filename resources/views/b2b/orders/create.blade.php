<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Order B2B — APM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

@include('partials._navbar_b2b')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#CD422B] transition font-medium">Dashboard</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('b2b.orders.index') }}" class="hover:text-[#CD422B] transition font-medium">Pesanan B2B</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-bold text-gray-700">Buat Order Baru</span>
    </nav>

    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900">Buat Order Pengadaan B2B</h1>
        <p class="text-sm text-gray-500 mt-1">Minimum 1 Ton · Maksimum 10 Ton · Pembayaran DP 50% + Pelunasan</p>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('b2b.orders.store') }}" id="b2bOrderForm">
        @csrf

        {{-- Info Rekening --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-6 flex gap-4">
            <div class="text-2xl">🏦</div>
            <div>
                <p class="font-bold text-blue-800 text-sm">Metode Pembayaran</p>
                <p class="text-xs text-blue-700 mt-1">Transfer Bank BCA (1234 5678 90) atau Bank DKI (0987 6543 21) — A/N PT. Agro Pangan Maju</p>
                <p class="text-xs text-blue-600 mt-0.5">Sistem DP 50% + Pelunasan 50% (tunjukkan ke driver sebelum barang diturunkan)</p>
            </div>
        </div>

        {{-- Pilih Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h2 class="font-bold text-gray-900 mb-5">Produk yang Dipesan</h2>

            <div id="productLines" class="space-y-4">
                <div class="product-line grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 block mb-1.5">Produk</label>
                        <select name="products[0][id]" class="product-select w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20" required onchange="updateProductInfo(this)">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price_b2b }}" data-unit="{{ $p->unit }}" data-stock="{{ $p->stock_b2b }}">
                                {{ $p->name }} ({{ $p->category->name ?? '-' }}) — Rp {{ number_format($p->price_b2b, 0, ',', '.') }}/KG
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1.5">Kuantitas (Ton)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="products[0][qty_ton]" min="1" max="10" step="0.5" placeholder="Min 1"
                                   class="qty-input w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20" required oninput="recalculate()" onblur="clampQty(this)">
                            <button type="button" onclick="removeLine(this)" class="text-red-400 hover:text-red-600 transition p-1 shrink-0" title="Hapus baris">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="sm:col-span-3 line-subtotal text-right text-xs text-gray-500 font-semibold"></div>
                </div>
            </div>

            <button type="button" onclick="addProductLine()"
                    class="mt-4 w-full border-2 border-dashed border-gray-200 hover:border-[#CD422B]/40 text-gray-400 hover:text-[#CD422B] font-semibold py-3 rounded-xl transition text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </button>

            {{-- Summary --}}
            <div id="orderSummary" class="hidden mt-6 bg-slate-50 rounded-xl p-5 border border-slate-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Total Berat</span>
                    <span id="totalWeight" class="font-bold text-gray-800 text-sm">0 Ton</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Estimasi Subtotal</span>
                    <span id="totalSubtotal" class="font-bold text-[#CD422B] text-sm">Rp 0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">DP 50% yang harus dibayar</span>
                    <span id="totalDp" class="font-black text-blue-700">Rp 0</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">*Ongkos kirim armada akan ditambahkan oleh admin</p>
                <div id="weightWarning" class="hidden mt-3 bg-red-50 text-red-700 text-xs font-semibold px-3 py-2 rounded-lg">
                    ⚠️ Total pesanan harus antara 1 – 10 Ton
                </div>
            </div>
        </div>

        {{-- Alamat Pengiriman --}}
        @php
            $reg = auth()->user()->b2bRegistration;
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8">
            <h2 class="font-bold text-xl text-gray-900 mb-6">Alamat Penerimaan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase letter-spacing-wider block">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', $reg->pic_name) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase letter-spacing-wider block">No. Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_phone" value="{{ old('recipient_phone', $reg->phone) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                </div>
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase letter-spacing-wider block">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="recipient_address" rows="3" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 resize-none transition-all">{{ old('recipient_address', $reg->company_address) }}</textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase letter-spacing-wider block">Kota / Kabupaten <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_city" value="{{ old('recipient_city', $reg->company_city) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase letter-spacing-wider block">Catatan (opsional)</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Misal: pintu belakang gudang..."
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn"
                class="w-full bg-[#CD422B] hover:bg-red-700 text-white font-black py-4 rounded-2xl transition shadow-lg text-base flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-[#CD422B]">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Kirim Order Pengadaan
        </button>

    </form>
</div>

<script>
let lineIndex = 1;

const productsData = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price_b2b]));

function formatRp(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

function recalculate() {
    let totalWeight = 0;
    let totalSubtotal = 0;
    let valid = true;

    document.querySelectorAll('.product-line').forEach(line => {
        const select = line.querySelector('.product-select');
        const qty = parseFloat(line.querySelector('.qty-input').value) || 0;
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option?.dataset?.price || 0);

        if (select.value && qty > 0 && price > 0) {
            const subtotal = qty * 1000 * price;
            totalWeight += qty;
            totalSubtotal += subtotal;
            line.querySelector('.line-subtotal').textContent = `${qty} Ton × Rp ${price.toLocaleString('id-ID')}/KG = ${formatRp(subtotal)}`;
        } else {
            line.querySelector('.line-subtotal').textContent = '';
        }
    });

    const summary = document.getElementById('orderSummary');
    const submitBtn = document.getElementById('submitBtn');

    if (totalWeight > 0) {
        summary.classList.remove('hidden');
        document.getElementById('totalWeight').textContent = totalWeight + ' Ton';
        document.getElementById('totalSubtotal').textContent = formatRp(totalSubtotal);
        document.getElementById('totalDp').textContent = formatRp(totalSubtotal * 0.5);

        const warning = document.getElementById('weightWarning');
        if (totalWeight < 1 || totalWeight > 10) {
            warning.classList.remove('hidden');
            valid = false;
        } else {
            warning.classList.add('hidden');
        }
    } else {
        summary.classList.add('hidden');
        valid = false; // Disable button if total weight is 0
    }

    submitBtn.disabled = !valid;
}

function addProductLine() {
    const container = document.getElementById('productLines');
    const options = document.querySelectorAll('.product-select')[0].innerHTML;
    const html = `
        <div class="product-line grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <div class="sm:col-span-2">
                <label class="text-xs font-semibold text-gray-600 block mb-1.5">Produk</label>
                <select name="products[${lineIndex}][id]" class="product-select w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20" required onchange="recalculate()">
                    ${options}
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 block mb-1.5">Kuantitas (Ton)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="products[${lineIndex}][qty_ton]" min="1" max="10" step="0.5" placeholder="Min 1"
                           class="qty-input w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20" required oninput="recalculate()" onblur="clampQty(this)">
                    <button type="button" onclick="removeLine(this)" class="text-red-400 hover:text-red-600 transition p-1 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="sm:col-span-3 line-subtotal text-right text-xs text-gray-500 font-semibold"></div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    lineIndex++;
}

function removeLine(btn) {
    const lines = document.querySelectorAll('.product-line');
    if (lines.length <= 1) return;
    btn.closest('.product-line').remove();
    recalculate();
}

function updateProductInfo(select) {
    recalculate();
}

function clampQty(input) {
    let v = parseFloat(input.value);
    if (isNaN(v)) return;
    if (v < 1) input.value = 1;
    if (v > 10) input.value = 10;
    recalculate();
}

document.getElementById('b2bOrderForm').addEventListener('submit', function(e) {
    let totalWeight = 0;
    document.querySelectorAll('.qty-input').forEach(input => {
        totalWeight += parseFloat(input.value) || 0;
    });

    if (totalWeight < 1 || totalWeight > 10) {
        e.preventDefault();
        alert('Total pesanan harus antara 1 Ton sampai 10 Ton!');
    }
});
</script>


</body>
</html>
