<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Agro Pangan Maju - The Best Supplier on Earth</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white text-gray-800">

    @include('partials._navbar')


    <!-- 2. Hero Section -->
    <section class="w-full px-4 lg:px-12 mt-4 lg:mt-10 mb-20 relative">
        <div class="max-w-7xl mx-auto relative">
        <div class="w-full bg-[#CD422B] rounded-l-3xl rounded-br-[6rem] relative flex items-center min-h-[450px] lg:h-[500px]">
            <!-- Red Area Content -->
            <div class="w-full lg:w-1/2 p-10 lg:pl-16 z-10 text-white">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-2 tracking-wide">
                    PT. AGRO<br>PANGAN<br>MAJU
                </h1>
                <p class="text-lg lg:text-xl font-light italic mt-4 opacity-90">The Best Supplier on Earth</p>
                <div class="w-16 h-1 bg-white mt-6 rounded-full opacity-60 mb-10"></div>
                
                <!-- CTA Button -->
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-[#CD422B] font-black text-sm uppercase tracking-widest rounded-md hover:bg-gray-100 hover:scale-105 transition-all duration-300 shadow-2xl gap-3 ring-4 ring-white/20 group">
                    Lihat Katalog Produk
                    <svg class="w-5 h-5 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                    </svg>
                </a>
            </div>
            <!-- Offset Image Area -->
            <div class="hidden lg:block absolute top-10 -right-4 bottom-10 w-1/2">
                <div class="w-full h-full p-2 bg-white/20 backdrop-blur-sm rounded-xl overflow-hidden shadow-2xl relative">
                   <div class="w-full h-full bg-cover bg-center rounded-lg relative" style="background-image: url('{{ asset('images/Gedung-APM.webp') }}');">
                       <!-- Decorative Lines over Image -->
                       <div class="absolute inset-x-0 bottom-0 h-10 w-full bg-[#CD422B]"></div>
                   </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- 3. Client Logos (Top 5 Clients) -->
    <section class="max-w-6xl mx-auto text-center mb-24 px-6 z-20 relative">
        <h3 class="text-lg font-bold text-gray-800 mb-8 flex items-center justify-center gap-2">
            Our Top <span class="bg-[#F6A02B] text-white px-2 py-0.5 rounded-sm">5</span> Clients
        </h3>
        <div class="flex flex-wrap lg:flex-nowrap justify-center lg:justify-between items-center gap-8 lg:gap-14 opacity-70 grayscale hover:grayscale-0 transition duration-500">
            <!-- Actual Logos -->
            <img src="{{ asset('images/Swissbel-Hotel.webp') }}" class="h-8 md:h-12 lg:h-14 w-auto object-contain shrink-0" alt="Swiss-Belhotel">
            <img src="{{ asset('images/Bulog.webp') }}" class="h-8 md:h-12 lg:h-14 w-auto object-contain shrink-0" alt="Perum Bulog">
            <img src="{{ asset('images/Pagi Sore.webp') }}" class="h-12 md:h-16 lg:h-20 w-auto object-contain shrink-0" alt="Pagi Sore">
            <img src="{{ asset('images/Pasar Buah.webp') }}" class="h-8 md:h-12 lg:h-14 w-auto object-contain shrink-0" alt="Pasar Buah Pekanbaru">
            <img src="{{ asset('images/Prime Park.webp') }}" class="h-8 md:h-12 lg:h-14 w-auto object-contain shrink-0" alt="Prime Park Hotel">
        </div>
    </section>

    <!-- 4. Product Showcase & Reviews (Red Wavy Section) -->
    <section id="products" class="w-full relative bg-[#CD422B] mt-40 pb-20 pt-16 -mb-1">
        <!-- Top Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none z-0 transform -translate-y-[99%]">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-[60px] lg:h-[120px]">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C69.67,36.56,151.81,56.24,228.37,60.89C259.88,62.8,290.73,62.08,321.39,56.44Z" fill="#CD422B"></path>
            </svg>
        </div>

        <div class="max-w-6xl mx-auto px-6 text-center text-white relative z-10 pt-4">
            <h2 class="text-3xl lg:text-4xl font-extrabold mb-1">The Best Product</h2>
            <h2 class="text-3xl lg:text-4xl font-extrabold mb-4">You Can Get on Earth</h2>
            <p class="text-white/80 max-w-2xl mx-auto text-sm lg:text-base leading-relaxed mb-12">
                PT. Agro Pangan Maju becomes a single, integrated source to assure product quality, standard delivery, and zero errors with real time data sharing within e-commerce system.
            </p>

            <!-- Product Circles -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-10 md:gap-16 lg:gap-24 mb-24">
                <!-- Seafood -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 lg:w-56 lg:h-56 rounded-full border-[6px] border-white/20 p-2 mb-4">
                        <img src="{{ asset('images/Seafood.webp') }}" class="w-full h-full object-cover rounded-full shadow-xl" alt="Seafood">
                        <!-- Red Arc Decoration -->
                        <div class="absolute inset-0 border-[6px] border-transparent border-t-white border-l-white rounded-full opacity-80 mix-blend-overlay"></div>
                    </div>
                    <span class="font-bold text-xl tracking-wide">Seafood</span>
                </div>
                <!-- Poultry -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 lg:w-56 lg:h-56 rounded-full border-[6px] border-white/20 p-2 mb-4">
                        <img src="{{ asset('images/Poultry.webp') }}" class="w-full h-full object-cover rounded-full shadow-xl" alt="Poultry">
                        <div class="absolute inset-0 border-[6px] border-transparent border-t-white border-r-white rounded-full opacity-80 mix-blend-overlay"></div>
                    </div>
                    <span class="font-bold text-xl tracking-wide">Poultry</span>
                </div>
                <!-- Meat -->
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 lg:w-56 lg:h-56 rounded-full border-[6px] border-white/20 p-2 mb-4">
                        <img src="{{ asset('images/Meat.webp') }}" class="w-full h-full object-cover rounded-full shadow-xl" alt="Meat">
                        <div class="absolute inset-0 border-[6px] border-transparent border-b-white border-l-white rounded-full opacity-80 mix-blend-overlay"></div>
                    </div>
                    <span class="font-bold text-xl tracking-wide">Meat</span>
                </div>
            </div>

            <!-- Customer Reviews Header -->
            <h2 class="text-3xl font-extrabold mb-10">Ulasan Mitra Kami</h2>

            <!-- Review Carousel -->
            <style>
                @keyframes scroll {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(calc(-50% - 1rem)); } /* -50% plus half gap */
                }
                .animate-scroll {
                    display: flex;
                    width: max-content;
                    animation: scroll 40s linear infinite;
                }
                .animate-scroll:hover {
                    animation-play-state: paused;
                }
                .scroll-container {
                    overflow: hidden;
                    width: 100vw;
                    position: relative;
                    left: 50%;
                    right: 50%;
                    margin-left: -50vw;
                    margin-right: -50vw;
                }
            </style>

            <div class="scroll-container relative z-20" style="margin-bottom: -150px;">
                <div class="animate-scroll gap-6 lg:gap-8 px-4">
                    @php
                        $reviews = [
                            ['logo' => 'Prime Park.webp', 'name' => 'PRIME PARK HOTEL', 'text' => 'APM memberikan solusi pengadaan bahan baku yang sangat terintegrasi. Kualitas terjamin, pengiriman konsisten, dan sangat memudahkan manajemen persediaan hotel kami.', 'author' => 'Executive Chef'],
                            ['logo' => 'Pasar Buah.webp', 'name' => 'PASAR BUAH PEKANBARU', 'text' => 'Sistem e-commerce dan layanan B2B dari APM benar-benar bebas hambatan. Responsif, stok transparan, dan sangat membantu kami memenuhi kebutuhan operasional sehari-hari.', 'author' => 'Purchasing Manager'],
                            ['logo' => 'Swissbel-Hotel.webp', 'name' => 'SWISS-BELHOTEL', 'text' => 'Layanan logistik rantai dingin (cold chain) APM memastikan daging dan seafood kami selalu tiba dalam kondisi paling segar. Standar pengiriman sangat profesional.', 'author' => 'F&B Director'],
                            ['logo' => 'Pagi Sore.webp', 'name' => 'RESTORAN PAGI SORE', 'text' => 'Kualitas daging dan ayam potong dari APM sangat terjaga konsistensinya. Sangat krusial bagi bisnis kuliner kami untuk terus menyajikan masakan berkualitas premium.', 'author' => 'Operational Manager'],
                            ['logo' => 'Bulog.webp', 'name' => 'PERUM BULOG', 'text' => 'Akurasi data dan transparansi sistem APM sangat patut diacungi jempol. Selalu tepat waktu dan menjadi mitra distribusi pangan berskala regional yang sangat dapat diandalkan.', 'author' => 'Logistics Coordinator']
                        ];
                        // Duplicate array to create a seamless infinite loop
                        $allReviews = array_merge($reviews, $reviews);
                    @endphp

                    @foreach($allReviews as $r)
                    <div class="w-[320px] md:w-[450px] bg-white rounded-2xl p-8 shadow-2xl flex flex-col justify-between shrink-0">
                        <div class="flex flex-col items-center justify-center mb-6 h-16">
                            <img src="{{ asset('images/' . $r['logo']) }}" alt="{{ $r['name'] }}" class="h-14 w-auto object-contain">
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 italic text-center">
                            "{{ $r['text'] }}"
                        </p>
                        <div class="flex items-center justify-between mt-auto border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-[#CD422B] font-bold text-sm border-2 border-white shadow-sm">
                                    {{ substr($r['author'], 0, 1) }}
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="font-bold text-gray-800 text-sm">{{ $r['author'] }}</span>
                                    <span class="text-[10px] text-gray-500">{{ $r['name'] }}</span>
                                </div>
                            </div>
                            <div class="flex text-[#F6A02B]">
                                @for($i=0; $i<5; $i++)
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Services Section (Peach Background) -->
    <section id="services" class="w-full bg-[#FAD9D1] pt-56 pb-20 px-6 relative z-0">
        <!-- We use padding-top 56 (224px) to make room for the overflowing review cards from above -->
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Our Services</h2>
            <p class="text-gray-700 max-w-3xl mx-auto text-sm lg:text-base mb-12">
                PT. Agro Pangan Maju menyediakan layanan distribusi dan yang kami tawarkan mencakup berbagai aspek rantai pasokan makanan berikut adalah beberapa layanan yang umumnya kami tawarkan
            </p>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <!-- S1: Penyedia Bahan Baku -->
                <div class="bg-white/80 backdrop-blur rounded-2xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/Penyedia Bahan Baku.webp') }}" alt="Penyedia Bahan Baku" class="w-14 h-14 object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 leading-tight">Penyedia<br>Bahan Baku</h4>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">Perusahaan layanan makanan menyediakan bahan baku berkualitas tinggi Ini mencakup Meat Poultry & Seafood.</p>
                </div>

                <!-- S2: Distribusi dan Pengiriman -->
                <div class="bg-white/80 backdrop-blur rounded-2xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/Distribusi Pengiriman.webp') }}" alt="Distribusi dan Pengiriman" class="w-14 h-14 object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 leading-tight">Distribusi dan<br>Pengiriman</h4>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">Dapat menangani distribusi dan pengiriman produk makanan beku ke berbagai lokasi. Kami menyediakan armada pengiriman yang dilengkapi dengan fasilitas pengaturan suhu untuk memastikan produk tetap beku selama pengiriman.</p>
                </div>

                <!-- S3: Jasa Pengolahan -->
                <div class="bg-white/80 backdrop-blur rounded-2xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/Jasa Pengelola.webp') }}" alt="Jasa Pengolahan" class="w-14 h-14 object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 leading-tight">Jasa<br>Pengolahan</h4>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">Menawarkan jasa pengolahan makanan, seperti pemotongan daging Ini membantu bisnis makanan menghemat waktu dan tenaga dalam mempersiapkan bahan-bahan makanan mereka.</p>
                </div>

                <!-- S4: Penyimpanan Beku -->
                <div class="bg-white/80 backdrop-blur rounded-2xl p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 shrink-0 flex items-center justify-center">
                            <img src="{{ asset('images/Penyimpanan Beku.webp') }}" alt="Penyimpanan Beku" class="w-14 h-14 object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <h4 class="font-bold text-lg text-gray-800 leading-tight">Penyimpanan<br>Beku</h4>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">Menyediakan fasilitas penyimpanan dingin yang dilengkapi dengan sistem pendingin yang tepat untuk menjaga suhu ruangan dalam rentang yang aman untuk produk makanan.</p>
                </div>
            </div>
        </div>
    </section>




    <!-- 7. Distribution Section -->
    <section class="max-w-5xl mx-auto px-6 py-20 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Our Distribution</h2>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm mb-10">
            PT. Agro Pangan Maju secara aktif telah bermitra dengan lebih dari 10.000 konsumen berstatus "repeat booking" di beberapa kota dan kepulauan strategis Indonesia.
        </p>

        <div class="w-full flex items-center justify-center mt-10">
            <img src="{{ asset('images/Peta-Distribusi.webp') }}" class="w-full max-w-5xl object-contain drop-shadow-xl" alt="Peta Distribusi Agro Pangan Maju">
        </div>
    </section>

    <!-- 8. Footer -->
    @include('partials._footer')

</body>
</html>
