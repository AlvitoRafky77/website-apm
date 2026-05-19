<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ — PT. Agro Pangan Maju</title>
    <meta name="description" content="Pertanyaan yang sering diajukan seputar layanan PT. Agro Pangan Maju.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease; }
        .faq-answer.open { max-height: 500px; }
        .faq-icon { transition: transform 0.3s ease; }
        .faq-icon.rotate { transform: rotate(45deg); }
        .faq-item { transition: all 0.2s ease; }
        .faq-item:hover { border-color: #CD422B; }
        .faq-item.active { border-color: #CD422B; box-shadow: 0 4px 20px -8px rgba(205, 66, 43, 0.15); }
        .tab-btn.active { background: #CD422B; color: white; }
        .faq-group { display: none; }
        .faq-group.active { display: block; }
    </style>
</head>
<body class="antialiased bg-white text-gray-800">

    @include('partials._navbar')

    {{-- HERO --}}
    <section class="w-full bg-gradient-to-r from-[#A82818] via-[#CD422B] to-[#E3542B] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 h-40 border-2 border-white rounded-full"></div>
            <div class="absolute bottom-5 right-20 w-60 h-60 border border-white rounded-full"></div>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-32 pb-16 relative z-10 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/60 mb-3">Pusat Bantuan</p>
            <h1 class="text-4xl lg:text-5xl font-black text-white drop-shadow-sm tracking-wide mb-4">Pertanyaan Umum (FAQ)</h1>
            <p class="text-white/80 max-w-xl mx-auto text-sm lg:text-base leading-relaxed">Temukan jawaban atas pertanyaan yang paling sering ditanyakan seputar layanan, produk, dan kemitraan bersama PT. Agro Pangan Maju.</p>
        </div>
    </section>

    {{-- FAQ CONTENT --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6 lg:px-12">

            {{-- Category Tabs --}}
            <div class="flex flex-wrap justify-center gap-2 mb-10">
                @foreach([
                    ['id' => 'umum', 'label' => '🏢 Umum'],
                    ['id' => 'produk', 'label' => '📦 Produk'],
                    ['id' => 'b2c', 'label' => '🛒 Belanja B2C'],
                    ['id' => 'b2b', 'label' => '🤝 Kemitraan B2B'],
                    ['id' => 'pengiriman', 'label' => '🚚 Pengiriman'],
                    ['id' => 'akun', 'label' => '👤 Akun'],
                ] as $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')"
                        id="tab-{{ $tab['id'] }}"
                        class="tab-btn px-4 py-2 rounded-full text-xs font-bold border border-gray-200 text-gray-600 hover:border-[#CD422B] hover:text-[#CD422B] transition {{ $loop->first ? 'active' : '' }}">
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>

            {{-- FAQ: Umum --}}
            <div id="group-umum" class="faq-group active space-y-3">
                @foreach([
                    ['q' => 'Apa itu PT. Agro Pangan Maju?', 'a' => 'PT. Agro Pangan Maju (APM) adalah perusahaan distributor bahan baku pangan terpercaya yang bergerak di bidang Meat, Poultry, dan Seafood. Kami melayani kebutuhan Hotel, Restoran, Katering, serta pelanggan retail di seluruh Indonesia.'],
                    ['q' => 'Dimana lokasi operasional APM?', 'a' => 'Lokasi operasional kami berada di Pergudangan Panam Bizpark No.B-19, Pekanbaru, Riau. Anda bisa mengunjungi kami pada jam operasional atau menghubungi via WhatsApp terlebih dahulu.'],
                    ['q' => 'Apa saja jam operasional APM?', 'a' => 'Jam operasional kami adalah Senin–Sabtu pukul 08.00–17.00 WIB. Di luar jam tersebut, Anda tetap bisa mengirimkan pesan melalui formulir Contact Us dan tim kami akan merespon pada jam kerja berikutnya.'],
                    ['q' => 'Bagaimana cara menghubungi APM?', 'a' => 'Anda dapat menghubungi kami melalui: (1) Formulir Contact Us di halaman Kontak website, (2) WhatsApp Business yang tersedia di website, atau (3) Chatbot APM Assistant yang aktif 24 jam untuk menjawab pertanyaan dasar.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

            {{-- FAQ: Produk --}}
            <div id="group-produk" class="faq-group space-y-3">
                @foreach([
                    ['q' => 'Produk apa saja yang tersedia di APM?', 'a' => 'Kami menyediakan berbagai produk bahan baku pangan berkualitas yang terbagi dalam kategori utama: Poultry (ayam), Seafood (hasil laut), dan Meat (daging sapi/kambing). Semua produk kami dijaga kesegarannya melalui sistem cold chain.'],
                    ['q' => 'Apakah produk APM dijamin kualitasnya?', 'a' => 'Ya, setiap produk kami melewati kontrol kualitas ketat dan disimpan dalam gudang berpendingin (cold storage) berteknologi tinggi. Kami berkomitmen memenuhi standar keamanan pangan untuk menjamin kesegaran hingga sampai ke tangan pelanggan.'],
                    ['q' => 'Bagaimana cara melihat katalog produk?', 'a' => 'Anda bisa mengakses katalog produk lengkap melalui menu "Produk" di website kami. Katalog telah dikelompokkan berdasarkan kategori sehingga memudahkan Anda menemukan produk yang dibutuhkan.'],
                    ['q' => 'Apakah harga produk B2C dan B2B berbeda?', 'a' => 'Ya, harga B2B (mitra bisnis) biasanya lebih kompetitif karena pembelian dalam jumlah besar (partai ton). Harga B2C mengikuti harga retail per kilogram yang tertera di katalog produk.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

            {{-- FAQ: B2C --}}
            <div id="group-b2c" class="faq-group space-y-3">
                @foreach([
                    ['q' => 'Bagaimana cara mendaftar sebagai pelanggan B2C?', 'a' => 'Klik tombol "Daftar" di pojok kanan atas, lalu pilih "Daftar sebagai Pelanggan (B2C)". Isi data diri Anda (nama, email, password, nomor HP) lalu klik Daftar. Akun Anda langsung aktif dan bisa digunakan untuk belanja.'],
                    ['q' => 'Bagaimana cara melakukan pemesanan?', 'a' => 'Setelah login, buka halaman Produk, klik produk yang diinginkan, lalu tambahkan ke keranjang. Setelah selesai memilih, buka keranjang dan lanjutkan ke halaman Checkout untuk menyelesaikan pembayaran.'],
                    ['q' => 'Metode pembayaran apa yang tersedia?', 'a' => 'Kami menggunakan payment gateway Midtrans yang mendukung berbagai metode pembayaran seperti transfer bank, e-wallet (GoPay, ShopeePay), kartu kredit/debit, dan virtual account dari berbagai bank.'],
                    ['q' => 'Bagaimana cara mengecek status pesanan saya?', 'a' => 'Setelah login, buka menu "Pesanan Saya" untuk melihat seluruh riwayat dan status pesanan Anda secara real-time, mulai dari pembayaran, proses pengiriman, hingga pesanan selesai.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

            {{-- FAQ: B2B --}}
            <div id="group-b2b" class="faq-group space-y-3">
                @foreach([
                    ['q' => 'Bagaimana cara mendaftar sebagai Mitra B2B?', 'a' => 'Klik "Daftar" lalu pilih "Daftar sebagai Mitra B2B". Lengkapi data PIC, data perusahaan, dan upload dokumen MoU. Setelah dikirim, status pendaftaran Anda akan ditinjau oleh Admin. Jika disetujui, Anda akan menerima email berisi kredensial akun B2B.'],
                    ['q' => 'Berapa minimum pembelian untuk B2B?', 'a' => 'Minimum pembelian B2B adalah 1 Ton (1.000 KG) per transaksi dan maksimum 10 Ton (10.000 KG) per transaksi.'],
                    ['q' => 'Bagaimana sistem pembayaran B2B?', 'a' => 'Sistem pembayaran B2B menggunakan skema DP 50% di awal untuk konfirmasi order, lalu pelunasan 50% sisanya sebelum barang diturunkan dari armada. Bukti transfer wajib diupload melalui portal B2B.'],
                    ['q' => 'Apakah ada fitur inspeksi barang untuk B2B?', 'a' => 'Ya, setelah barang tiba, mitra B2B dapat melakukan inspeksi dan melaporkan kondisi barang melalui portal. Jika ditemukan kerusakan, sistem dispute akan diproses oleh Admin untuk penyelesaian.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

            {{-- FAQ: Pengiriman --}}
            <div id="group-pengiriman" class="faq-group space-y-3">
                @foreach([
                    ['q' => 'Bagaimana sistem pengiriman APM?', 'a' => 'Untuk B2C, pengiriman menggunakan jasa kurir pihak ketiga (Lalamove). Untuk B2B, pengiriman menggunakan armada pendingin milik APM sendiri untuk menjaga kualitas produk selama perjalanan.'],
                    ['q' => 'Berapa lama estimasi pengiriman?', 'a' => 'Estimasi pengiriman B2C tergantung jarak dan layanan kurir yang dipilih saat checkout. Untuk B2B, jadwal pengiriman akan dikonfirmasi oleh Admin setelah DP diverifikasi dan armada dijadwalkan.'],
                    ['q' => 'Apakah ada biaya pengiriman?', 'a' => 'Ya, biaya pengiriman B2C dihitung otomatis saat checkout berdasarkan alamat tujuan. Untuk B2B, ongkos kirim armada APM akan diinformasikan terpisah saat proses order.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

            {{-- FAQ: Akun --}}
            <div id="group-akun" class="faq-group space-y-3">
                @foreach([
                    ['q' => 'Bagaimana cara mengubah data profil saya?', 'a' => 'Login ke akun Anda, lalu klik nama/avatar Anda di pojok kanan atas dan pilih "Profil". Di halaman tersebut Anda bisa memperbarui informasi pribadi, data perusahaan (untuk B2B), dan password.'],
                    ['q' => 'Saya lupa password, bagaimana cara reset?', 'a' => 'Di halaman Login, klik "Lupa Password?". Masukkan email Anda dan sistem akan mengirimkan link reset password ke email tersebut. Ikuti instruksi di email untuk membuat password baru.'],
                    ['q' => 'Apakah akun saya bisa dihapus?', 'a' => 'Ya, untuk pengguna B2C terdapat opsi "Hapus Akun" di halaman Profil. Anda perlu memasukkan password untuk konfirmasi. Untuk akun B2B, penghapusan akun perlu koordinasi dengan Admin.'],
                ] as $faq)
                @include('pages._faq_item', $faq)
                @endforeach
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gradient-to-r from-[#1a1a2e] to-[#2d1b0e] py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-2xl lg:text-3xl font-black text-white mb-3">Masih Ada Pertanyaan?</h2>
            <p class="text-white/60 text-sm mb-8 max-w-lg mx-auto">Tim kami siap membantu Anda. Hubungi kami melalui halaman kontak atau langsung via WhatsApp.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-[#CD422B] hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition shadow-lg hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Hubungi Kami
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition shadow-lg hover:-translate-y-0.5">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.12.553 4.106 1.519 5.83L0 24l6.336-1.48A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
                    Chat via WhatsApp
                </a>
            </div>
        </div>
    </section>

    @include('partials._footer')

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.faq-group').forEach(g => g.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            document.getElementById('group-' + tabId).classList.add('active');
        }

        document.querySelectorAll('.faq-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.faq-item');
                const answer = item.querySelector('.faq-answer');
                const icon = item.querySelector('.faq-icon');
                const isOpen = answer.classList.contains('open');

                // Close all others in same group
                item.closest('.faq-group').querySelectorAll('.faq-item').forEach(other => {
                    if (other !== item) {
                        other.classList.remove('active');
                        other.querySelector('.faq-answer').classList.remove('open');
                        other.querySelector('.faq-answer').style.padding = '0 24px';
                        other.querySelector('.faq-icon').classList.remove('rotate');
                    }
                });

                if (isOpen) {
                    answer.classList.remove('open');
                    answer.style.padding = '0 24px';
                    icon.classList.remove('rotate');
                    item.classList.remove('active');
                } else {
                    answer.classList.add('open');
                    answer.style.padding = '0 24px 20px';
                    icon.classList.add('rotate');
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
