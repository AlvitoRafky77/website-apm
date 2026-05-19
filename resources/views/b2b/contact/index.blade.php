<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungi Admin B2B — APM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background: #f8f9fb; }</style>
</head>
<body class="antialiased min-h-screen">

@include('partials._navbar_b2b')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#CD422B] transition font-medium">Dashboard</a>
        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-bold text-gray-700">Hubungi Admin</span>
    </nav>

    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900">Hubungi Tim APM</h1>
        <p class="text-sm text-gray-500 mt-1">Sampaikan kendala order, negosiasi harga, atau pengajuan pengiriman khusus.</p>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
        <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="font-bold text-emerald-900 text-sm">Pesan berhasil dikirim!</p>
            <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8">
        @php
            $reg = $user->b2bRegistration;
        @endphp

        <form method="POST" action="{{ route('b2b.contact.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Pengirim</label>
                    <input type="text" name="name" value="{{ $user->name }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Perusahaan</label>
                    <input type="text" name="company" value="{{ $reg->company_name ?? '-' }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Akun</label>
                    <input type="email" name="email" value="{{ $user->email }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">No. WhatsApp / Telepon</label>
                    <input type="text" name="phone" value="{{ $reg->phone ?? '-' }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Topik Pesan <span class="text-red-500">*</span></label>
                <select name="topic" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20">
                    <option value="">-- Pilih Topik --</option>
                    <option value="Kendala Order & Pengiriman">Kendala Order & Pengiriman</option>
                    <option value="Pertanyaan Stok & Produk">Pertanyaan Stok & Produk</option>
                    <option value="Negosiasi Harga / Request Kuantitas Besar">Negosiasi Harga / Request Kuantitas Besar</option>
                    <option value="Kendala Pembayaran & Invoice">Kendala Pembayaran & Invoice</option>
                    <option value="Perubahan Alamat Kirim">Perubahan Alamat Kirim</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Isi Pesan <span class="text-red-500">*</span></label>
                <textarea name="message" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 resize-none" placeholder="Tuliskan detail pertanyaan atau kendala Anda di sini..."></textarea>
                <p class="text-[10px] text-gray-400 mt-2">Pesan akan masuk ke sistem antrean khusus mitra B2B (Priority Response).</p>
            </div>

            <div class="pt-4 flex items-center justify-between">
                <a href="https://wa.me/6285274331328" target="_blank" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Chat via WhatsApp
                </a>
                <button type="submit" class="bg-[#CD422B] hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-red-900/20 text-sm">
                    Kirim Pesan B2B
                </button>
            </div>
        </form>
    </div>
</div>


</body>
</html>
