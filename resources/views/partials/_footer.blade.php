{{-- ===== UNIFIED FOOTER — PT. Agro Pangan Maju ===== --}}
<footer class="relative text-white overflow-hidden text-sm">
    {{-- Background image with dark overlay --}}
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/Footer Section.webp') }}')"></div>
    <div class="absolute inset-0 bg-gray-900/90"></div>

    {{-- Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 pt-16 pb-6">

        {{-- Top grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 pb-12">

            {{-- Column 1: Brand & Contact (Wider) --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/APM_Logo.webp') }}" alt="Logo APM" class="h-16 w-16 object-contain">
                    <div>
                        <h4 class="font-extrabold text-2xl tracking-tight leading-tight">Agro Pangan Maju</h4>
                        <p class="text-white/60 text-[10px] mt-1 font-medium tracking-wide">The Best Food Supplier on Earth</p>
                    </div>
                </div>
                <p class="text-white/80 leading-relaxed mb-6 max-w-xs font-light">
                    Pergudangan Panam Bizpark No.B-19 Jl.Raya Pekan
                    Baru - Bangkinang, Kel.Tuah Madani, Kec.Tampan, Kota
                    Pekan Baru, Riau
                </p>
                {{-- Certifications --}}
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/Halal-Logo.webp') }}" class="h-12 w-auto object-contain" alt="Halal">
                    <img src="{{ asset('images/Badan-Pom.webp') }}" class="h-12 w-auto object-contain" alt="BPOM">
                    <img src="{{ asset('images/NKV-Logo.webp') }}" class="h-16 w-auto object-contain bg-white rounded-md p-1" alt="NKV">
                </div>
            </div>

            {{-- Column 2: Products --}}
            <div class="lg:col-span-2 lg:ml-8">
                <h5 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">PRODUCTS</h5>
                <ul class="space-y-4 text-[#D1D5DB] font-light">
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Seafood</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Meat</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Poultry</a></li>
                </ul>
            </div>

            {{-- Column 3: Company --}}
            <div class="lg:col-span-2">
                <h5 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">COMPANY</h5>
                <ul class="space-y-4 text-[#D1D5DB] font-light">
                    <li><a href="{{ route('about') }}"      class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('contact') }}"    class="hover:text-white transition-colors">Partners</a></li>
                </ul>
            </div>

            {{-- Column 4: Support --}}
            <div class="lg:col-span-3">
                <h5 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">SUPPORT</h5>
                <ul class="space-y-4 text-[#D1D5DB] font-light mb-8">
                    <li><a href="#" class="hover:text-white transition-colors">FaQ</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Sales</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Report</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-dashed border-gray-600/50 pt-5 flex flex-col sm:flex-row items-center justify-between gap-4 text-[#9CA3AF] text-sm">
            <p>Copyright © {{ date('Y') }} Agro Pangan Maju</p>
            
            {{-- Social Media Icons (Orange without background) --}}
            <div class="flex items-center gap-5">
                {{-- Facebook --}}
                <a href="#" aria-label="Facebook" class="text-[#E3542B] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                {{-- Instagram --}}
                <a href="#" aria-label="Instagram" class="text-[#E3542B] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                </a>
                {{-- LinkedIn --}}
                <a href="#" aria-label="LinkedIn" class="text-[#E3542B] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z"/></svg>
                </a>
                {{-- WhatsApp --}}
                <a href="#" aria-label="WhatsApp" class="text-[#E3542B] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.868-2.03-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.15-.173.2-.297.298-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372C7.221 7.229 6.478 7.935 6.478 9.37c0 1.436 1.04 2.822 1.188 3.02.149.198 2.062 3.149 4.996 4.417 2.934 1.268 2.934.842 3.479.792.544-.05 1.758-.718 2.005-1.411.248-.694.248-1.288.173-1.411-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.766.458 3.424 1.26 4.887L2 22l5.244-1.127A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.3c-1.472 0-2.883-.377-4.103-1.077L4.78 19.9l1.41-2.923A8.258 8.258 0 014.3 12c0-4.254 3.456-7.7 7.7-7.7 4.253 0 7.7 3.446 7.7 7.7 0 4.254-3.447 7.7-7.7 7.7z"/></svg>
                </a>
                {{-- X (Twitter) --}}
                <a href="#" aria-label="X" class="text-[#E3542B] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
