<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us — PT. Agro Pangan Maju</title>
    <meta name="description" content="Mengenal lebih dekat PT. Agro Pangan Maju, distributor bahan baku pangan terpercaya di Indonesia.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .org-line::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: #CBD5E1;
        }
    </style>
</head>
<body class="antialiased bg-white text-gray-800">

    @include('partials._navbar')



    {{-- ===== 1. HERO — "Tentang Kami" ===== --}}
    <section class="w-full bg-gradient-to-r from-[#A82818] via-[#CD422B] to-[#E3542B] relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 pt-24 sm:pt-28 pb-4">
            {{-- Title --}}
            <div class="text-center mb-10 sm:mb-16">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white drop-shadow-sm tracking-wide">Tentang Kami</h1>
            </div>

            {{-- 4 Photo Grid - Staggered layout hanging over the bottom --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 relative z-10 translate-y-10 sm:translate-y-20">
                <div class="rounded-2xl sm:rounded-3xl overflow-hidden h-36 sm:h-48 md:h-60 shadow-2xl translate-y-6 sm:translate-y-12">
                    <img src="{{ asset('images/About 1 Head.webp') }}" alt="APM 1" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl sm:rounded-3xl overflow-hidden h-36 sm:h-48 md:h-60 shadow-2xl -translate-y-2 sm:-translate-y-4">
                    <img src="{{ asset('images/About 2 Head.webp') }}" alt="APM 2" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl sm:rounded-3xl overflow-hidden h-36 sm:h-48 md:h-60 shadow-2xl translate-y-3 sm:translate-y-6">
                    <img src="{{ asset('images/About 3 Head.webp') }}" alt="APM 3" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <div class="rounded-2xl sm:rounded-3xl overflow-hidden h-36 sm:h-48 md:h-60 shadow-2xl translate-y-8 sm:translate-y-16">
                    <img src="{{ asset('images/About 4 Head.webp') }}" alt="APM 4" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 2. SEKILAS TENTANG PT. AGRO PANGAN MAJU ===== --}}
    <section class="bg-white pt-28 sm:pt-40 pb-16 relative z-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900 mb-6 tracking-tight">
                    Sekilas tentang PT. Agro Pangan Maju
                </h2>
                <p class="text-lg text-gray-600 leading-relaxed font-medium">
                    Sejak awal berdiri, komitmen utama kami adalah mencapai keunggulan paripurna—menyediakan pasokan bahan makanan bermutu dan pelayanan tanpa kompromi demi kepuasan pelanggan berharga kami.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                {{-- Point 1 --}}
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-[#FAD9D1] rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Inovasi & Teknologi Pangan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Kami selalu mengikuti perkembangan teknologi industri makanan. Mulai dari pembangunan gudang penyimpanan berteknologi tinggi hingga penyempurnaan pengolahan, semua dirancang untuk menjamin kualitas dan kesegaran maksimal.
                    </p>
                </div>

                {{-- Point 2 --}}
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-[#FAD9D1] rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Distribusi Armada Pendingin</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Jarak bukan hambatan. Sistem pengiriman terencana kami mengandalkan armada handal yang dilengkapi mesin pendingin (<em class="italic">Cold Chain</em>), agar kesegaran produk Anda sampai tanpa risiko penurunan kualitas.
                    </p>
                </div>

                {{-- Point 3 --}}
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-[#FAD9D1] rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Bermitra Secara Global</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Tumbuh dari akar <em class="italic">Meat, Poultry & Seafood</em>, kami bangga bermitra dengan pemasok eksklusif baik domestik maupun mancanegara—siap memenuhi pasar Hotel, Restoran, Katering, dan Ritel.
                    </p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-[#CD422B] p-6 rounded-r-2xl">
                <p class="text-gray-800 font-semibold leading-relaxed text-sm md:text-base">
                    "Dengan dedikasi tinggi, PT. Agro Pangan Maju senantiasa memberikan produk berkualitas prima dengan harga terjangkau, pelayanan responsif, serta kemudahan akses distribusi secara merata ke seluruh Indonesia."
                </p>
            </div>
        </div>
    </section>

    {{-- ===== 3. COMPANY VALUE ===== --}}
    <section class="bg-[#FAD9D1] py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="mb-10">
                <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-2">Company Value</h2>
                <p class="text-gray-600 text-sm max-w-2xl">
                    Nilai-nilai inti PT. Agro Pangan Maju mencerminkan komitmen kami terhadap kualitas, inovasi, keandalan, dan kepuasan pelanggan dalam setiap aspek bisnis kami.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach([
                    ['title'=>'Kualitas',           'icon'=>'Icon Kualitas.webp',  'desc'=>'Kami memastikan setiap produk memenuhi standar kualitas tertinggi dan memenuhi kebutuhan pelanggan kami.'],
                    ['title'=>'Inovasi',             'icon'=>'Icon Inovasi.webp',   'desc'=>'Selalu berinovasi untuk menghadirkan solusi distribusi modern yang lebih efisien dan terpercaya.'],
                    ['title'=>'Keandalan',           'icon'=>'Icon Keandalan.webp', 'desc'=>'Kami berkomitmen untuk memberikan layanan yang dapat diandalkan, tepat waktu, dan konsisten setiap saat.'],
                    ['title'=>'Kepuasan Pelanggan',  'icon'=>'Icon Kepuasan.webp',  'desc'=>'Kepuasan pelanggan adalah prioritas utama kami, mendorong kami untuk selalu memberikan pelayanan terbaik.'],
                ] as $val)
                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group text-center">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/' . $val['icon']) }}" alt="{{ $val['title'] }}"
                             class="w-16 h-16 object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-2">{{ $val['title'] }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $val['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== 4. VISI & MISI (kept premium design) ===== --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="mb-10">
                <h2 class="text-2xl lg:text-3xl font-black text-gray-900">Visi dan Misi</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- VISI --}}
                <div class="bg-white rounded-3xl p-8 lg:p-10 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#CD422B]/5 rounded-full translate-x-10 -translate-y-10 group-hover:bg-[#CD422B]/10 transition-colors pointer-events-none"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-[#CD422B] rounded-2xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-4">Visi Kami</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Menjadi mitra terpercaya dan pilihan utama dalam penyediaan bahan makanan berkualitas tinggi, inovatif, dan berkelanjutan bagi pelanggan kami di seluruh Indonesia.
                        </p>
                    </div>
                </div>

                {{-- MISI --}}
                <div class="bg-[#CD422B] rounded-3xl p-8 lg:p-10 shadow-md relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full translate-x-16 -translate-y-16 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full -translate-x-10 translate-y-10 pointer-events-none"></div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-white mb-5">Misi Kami</h3>
                        <ul class="space-y-3">
                            @foreach([
                                'Menyediakan beragam pilihan makanan beku berkualitas tinggi yang memenuhi standar keamanan pangan dan kepuasan pelanggan.',
                                'Mengembangkan kemitraan strategis dengan produsen lokal dan internasional untuk memastikan ketersediaan bahan baku yang berkualitas dan berkelanjutan.',
                                'Berinovasi secara terus-menerus dalam produk untuk memenuhi tren pasar dan kebutuhan konsumen yang beragam.',
                                'Memastikan rantai pasokan yang andal dan berkelanjutan dengan memperhatikan aspek lingkungan, sosial, dan ekonomi.',
                                'Memberikan layanan pelanggan yang unggul dengan fokus pada responsif, fleksibilitas, dan kepuasan pelanggan.',
                                'Meningkatkan kesadaran tentang pentingnya makanan beku yang sehat, praktis, dan ramah lingkungan melalui edukasi dan kampanye publik.',
                            ] as $misi)
                            <li class="flex items-start gap-3 text-white/90">
                                <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm leading-relaxed">{{ $misi }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 6. TEAM PROFILES ===== --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-12 space-y-10">

            {{-- Profile Card Layout: alternating --}}
            @foreach([
                ['name'=>'Hendra Kusuma','role'=>'Direktur Utama','side'=>'left',
                 'desc'=>'Hendra Kusuma merupakan pemimpin visioner yang mendirikan PT. Agro Pangan Maju dengan visi besar untuk menghadirkan sistem distribusi pangan yang modern dan andal di Indonesia. Dengan latar belakang bisnis dan pengalaman lebih dari 15 tahun di industri pangan, beliau memimpin perusahaan menuju pertumbuhan yang berkelanjutan.'],
                ['name'=>'Rina Marlina','role'=>'Manager Operasional','side'=>'right',
                 'desc'=>'Sebagai Manager Operasional, beliau bertanggung jawab atas kelancaran seluruh proses operasional harian perusahaan, mulai dari pengadaan bahan baku, manajemen gudang, hingga koordinasi armada pengiriman untuk memastikan efisiensi dan ketepatan layanan.'],
                ['name'=>'Dimas Saputra','role'=>'Head of Sales & Marketing','side'=>'left',
                 'desc'=>'Memimpin tim penjualan dan pemasaran dalam mengembangkan jaringan mitra B2B di seluruh Indonesia. Dengan strategi pemasaran berbasis data dan pendekatan personal, beliau telah berhasil meningkatkan basis pelanggan perusahaan secara signifikan setiap tahunnya.'],
            ] as $profile)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                <div class="flex flex-col {{ $profile['side'] === 'right' ? 'md:flex-row-reverse' : 'md:flex-row' }} items-stretch">
                    {{-- Photo --}}
                    <div class="md:w-56 shrink-0 bg-[#FAD9D1] flex items-end justify-center pt-6 px-6">
                        <img src="{{ asset('images/Pegawai Contoh.webp') }}" alt="{{ $profile['name'] }}"
                             class="w-40 h-auto object-contain drop-shadow-md">
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 p-7 flex flex-col justify-center">
                        <div class="inline-block bg-[#CD422B] text-white text-xs font-bold px-3 py-1 rounded-full mb-3 w-fit">
                            {{ $profile['role'] }}
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-3">{{ $profile['name'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $profile['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    @include('partials._footer')

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
