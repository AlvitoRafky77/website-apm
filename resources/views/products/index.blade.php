<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk - Agro Pangan Maju</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased bg-white text-gray-800 flex flex-col min-h-screen">

    @include('partials._navbar')


    <main class="grow">
        <!-- 2. Hero Banner Produk -->
        <section class="w-full px-4 lg:px-12 mt-6">
            <div class="max-w-7xl mx-auto">
            <div class="w-full bg-[#CD422B] rounded-2xl sm:rounded-3xl h-[200px] sm:h-[280px] lg:h-[360px] relative overflow-hidden flex items-center shadow-xl">
                <!-- Title -->
                <div class="w-full sm:w-1/2 pl-6 sm:pl-12 lg:pl-20 z-10">
                    <h1 class="text-white text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-wide drop-shadow">Produk</h1>
                </div>

                <!-- Right Composition -->
                <div class="absolute right-0 top-0 w-[550px] h-full hidden md:block">
                    <!-- Seafood -->
                    <div class="absolute right-12 top-[-20px] z-10">
                        <div class="w-44 h-44 rounded-full border-[6px] border-white overflow-hidden shadow-lg bg-gray-200">
                            <img src="{{ asset('images/Seafood.webp') }}" class="w-full h-full object-cover grayscale-[0.2]" alt="Seafood">
                        </div>
                        <div class="absolute -left-20 top-16 bg-white rounded-full px-5 py-2 shadow-xl flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#CD422B]"></div><span class="font-bold text-gray-800 text-sm">Seafood</span>
                        </div>
                    </div>

                    <!-- Poultry -->
                    <div class="absolute left-8 top-[60px] z-20">
                        <div class="w-[220px] h-[220px] rounded-full border-[6px] border-white overflow-hidden shadow-xl bg-gray-200">
                            <img src="{{ asset('images/Poultry.webp') }}" class="w-full h-full object-cover" alt="Poultry">
                        </div>
                        <div class="absolute -left-16 bottom-8 bg-white rounded-full px-5 py-2 shadow-xl flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#CD422B]"></div><span class="font-bold text-gray-800 text-sm">Poultry</span>
                        </div>
                    </div>

                    <!-- Meat -->
                    <div class="absolute right-[-20px] bottom-[-20px] z-30">
                        <div class="w-56 h-56 rounded-full border-[6px] border-white overflow-hidden shadow-lg bg-gray-200">
                            <img src="{{ asset('images/Meat.webp') }}" class="w-full h-full object-cover" alt="Meat">
                        </div>
                        <div class="absolute top-12 -left-16 bg-white rounded-full px-5 py-2 shadow-xl flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#CD422B]"></div><span class="font-bold text-gray-800 text-sm">Meat</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- 3. Produk Unggulan -->
        @if($featuredProducts->count() > 0)
        <section class="max-w-5xl mx-auto px-4 mt-24 mb-20 text-center">
            <h2 class="text-3xl font-black text-gray-900 mb-16 tracking-tight">Produk Unggulan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 justify-items-center">
                @foreach($featuredProducts as $item)
                <div class="relative w-[260px] sm:w-[300px] h-[400px] sm:h-[440px]">
                    <!-- Arch Background (Solid Pinkish) -->
                    <div class="absolute top-0 w-full h-[350px] bg-[#FAD9D1] rounded-t-[150px] rounded-b-xl px-8 pt-14 shadow-sm text-center">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-2">
                            {{ $item->name }}
                        </h3>
                        <div class="w-8 h-[2px] bg-[#CD422B]/30 mx-auto mb-4"></div>
                        <p class="text-[13px] text-gray-600 leading-relaxed text-center px-1 overflow-hidden line-clamp-5">
                            {{ Str::limit($item->full_description ?? $item->description, 200) }}
                        </p>
                    </div>

                    <!-- Overlapping Image at the Bottom -->
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-[230px] h-[170px] rounded-xl shadow-xl overflow-hidden hover:scale-105 transition-transform duration-500 bg-white border-4 border-white">
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200"></div>
                        @endif
                    </div>

                    <a href="{{ route('product.show', $item->slug) }}" class="absolute inset-0 z-10 w-full h-full"></a>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- 4. Kategori Sections -->
        @if($categories->count() > 0)
        <section class="max-w-6xl mx-auto px-4 mb-32 space-y-16">
            @foreach($categories as $category)
            @if($category->products->count() > 0)
                <div class="bg-[#FAD9D1] rounded-[2rem] px-4 md:px-12 py-12 relative w-full h-auto">
                    <!-- Pill Header -->
                    <div class="absolute -top-5 left-8 lg:left-12 bg-[#DD5942] shadow-sm text-white font-bold text-lg px-8 py-2 rounded-full tracking-wide">
                        {{ $category->name }}
                    </div>

                    <!-- Slider Wrapper -->
                    <div class="relative group mt-6" x-data="{
                            scrollLeft() { $refs.slider{{$category->id}}.scrollBy({ left: -320, behavior: 'smooth' }); },
                            scrollRight() { $refs.slider{{$category->id}}.scrollBy({ left: 320, behavior: 'smooth' }); }
                        }">

                        <!-- Left Arrow (Inside Container) -->
                        <button @click="scrollLeft" class="absolute -left-2 md:-left-8 top-1/2 -translate-y-1/2 z-10 text-black hover:scale-125 transition hidden md:block focus:outline-none drop-shadow">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <!-- The Slider -->
                        <div x-ref="slider{{$category->id}}" class="flex gap-6 overflow-x-auto snap-x snap-mandatory py-4 scrollbar-hide px-2 md:px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @foreach($category->products as $item)
                                <!-- Product Card (Minimalist with High-Contrast Price & Stock) -->
                                <a href="{{ route('product.show', $item->slug) }}" class="shrink-0 w-[240px] sm:w-[280px] bg-white rounded-2xl shadow-sm hover:shadow-lg transition-transform duration-300 snap-start overflow-hidden group/item cursor-pointer block transform hover:-translate-y-2">
                                    <div class="h-48 w-full bg-gray-100 overflow-hidden relative border-b border-gray-100">
                                        @if($item->image)
                                            <img src="{{ $item->image }}" class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-500" alt="{{ $item->name }}">
                                        @endif
                                    </div>
                                    <div class="p-5 text-center">
                                        <h4 class="font-bold text-gray-900 text-base leading-snug mb-3 line-clamp-2 min-h-[2.5rem]">{{ $item->name }}</h4>
                                        
                                        <!-- Price & Stock Badges Container (Stacked Vertically) -->
                                        <div class="flex flex-col items-center justify-center gap-1.5 mb-3">
                                            <!-- Price Badge -->
                                            <span class="inline-flex items-baseline gap-1 bg-red-50 text-[#CD422B] px-3 py-1 rounded-lg border border-red-100 font-extrabold text-xs sm:text-sm">
                                                <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                <span class="text-[10px] font-medium text-gray-500">/ {{ $item->unit ?? 'kg' }}</span>
                                            </span>

                                            <!-- Stock Badge (Below Price) -->
                                            @if($item->stock_b2c > 0)
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg border border-emerald-200 font-bold text-xs">
                                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Stok: {{ $item->stock_b2c }} KG</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg border border-rose-200 font-bold text-xs">
                                                    Stok Habis
                                                </span>
                                            @endif
                                        </div>

                                        <div class="w-8 h-[2px] bg-gray-100 mx-auto mb-3"></div>

                                        <p class="text-[12px] text-gray-500 leading-relaxed line-clamp-2 min-h-[2.25rem]">{{ $item->description }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Right Arrow (Inside Container) -->
                        <button @click="scrollRight" class="absolute -right-2 md:-right-8 top-1/2 -translate-y-1/2 z-10 text-black hover:scale-125 transition hidden md:block focus:outline-none drop-shadow">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <style>
                            .scrollbar-hide::-webkit-scrollbar { display: none; }
                        </style>
                    </div>
                </div>
            @endif
            @endforeach
        </section>
        @endif
    </main>

    <!-- 5. Footer -->
    @include('partials._footer')
</body>
</html>
