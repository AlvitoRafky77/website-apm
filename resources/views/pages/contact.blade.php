<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Contact Us - PT. Agro Pangan Maju</title>
	<meta name="description" content="Hubungi PT. Agro Pangan Maju untuk kebutuhan pasokan meat, poultry, dan seafood berkualitas untuk bisnis Anda.">
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-800">

	@include('partials._navbar')

	<section class="relative overflow-hidden bg-gradient-to-br from-[#8E261B] via-[#CD422B] to-[#E86B32]">
		<div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.45) 1px, transparent 0); background-size: 22px 22px;"></div>
		<div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/15 blur-2xl"></div>
		<div class="absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-black/10 blur-2xl"></div>

		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 pt-20 pb-16 sm:pt-24 sm:pb-24 lg:pt-28 lg:pb-28">
			<div class="max-w-3xl">
				<p class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1.5 text-xs font-semibold tracking-wider text-white/90 uppercase">
					Contact Center APM
				</p>
				<h1 class="mt-5 text-3xl sm:text-4xl lg:text-6xl font-black leading-tight text-white tracking-tight">
					Hubungi Tim Kami
				</h1>
				<p class="mt-5 text-base lg:text-lg leading-relaxed text-white/90 max-w-2xl">
					Kami siap membantu kebutuhan pasokan pangan bisnis Anda secara cepat dan profesional. Sampaikan kebutuhan Anda melalui form, email, telepon, atau WhatsApp.
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="#contact-form" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-[#B83B3B] shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:bg-gray-50">
						Kirim Pesan Sekarang
					</a>
					<a href="https://wa.me/6285274331328" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl border border-white/50 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
						WhatsApp Business
					</a>
				</div>
			</div>
		</div>
	</section>

	<section class="relative -mt-10 lg:-mt-14 pb-12">
		<div class="max-w-7xl mx-auto px-6 lg:px-12">
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
				<div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-[0_14px_35px_-18px_rgba(0,0,0,0.25)]">
					<div class="mb-4 h-11 w-11 rounded-xl bg-red-50 text-[#B83B3B] flex items-center justify-center">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.895 1.368l1.02 3.058a2 2 0 01-.45 2.11l-1.21 1.21a16.001 16.001 0 006.586 6.586l1.21-1.21a2 2 0 012.11-.45l3.058 1.02A2 2 0 0121 18.72V22a2 2 0 01-2 2h-1C9.163 24 0 14.837 0 3V2a2 2 0 012-2h1z"/></svg>
					</div>
					<h3 class="text-sm font-black text-gray-900 uppercase tracking-wide">Telepon</h3>
					<p class="mt-2 text-sm text-gray-600">Butuh respons cepat dari tim sales dan support kami.</p>
					<a href="tel:+6285274331328" class="mt-3 inline-block text-sm font-bold text-[#B83B3B] hover:text-[#9A2E2E]">085274331328</a>
				</div>

				<div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-[0_14px_35px_-18px_rgba(0,0,0,0.25)]">
					<div class="mb-4 h-11 w-11 rounded-xl bg-orange-50 text-[#E86B32] flex items-center justify-center">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-3.5 3.5M16 12l-3.5-3.5M4 6h16M4 18h16"/></svg>
					</div>
					<h3 class="text-sm font-black text-gray-900 uppercase tracking-wide">Email</h3>
					<p class="mt-2 text-sm text-gray-600">Untuk inquiry bisnis, kemitraan, dan dokumen resmi.</p>
					<a href="mailto:hello@agropanganmaju.com" class="mt-3 inline-block text-sm font-bold text-[#B83B3B] hover:text-[#9A2E2E]">hello@agropanganmaju.com</a>
				</div>

				<div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-[0_14px_35px_-18px_rgba(0,0,0,0.25)]">
					<div class="mb-4 h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
					</div>
					<h3 class="text-sm font-black text-gray-900 uppercase tracking-wide">Jam Operasional</h3>
					<p class="mt-2 text-sm text-gray-600">Senin - Jumat: 08.00 - 17.00 WIB</p>
					<p class="mt-1 text-sm text-gray-600">Sabtu: 08.00 - 14.00 WIB</p>
				</div>

				<div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-[0_14px_35px_-18px_rgba(0,0,0,0.25)]">
					<div class="mb-4 h-11 w-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
					</div>
					<h3 class="text-sm font-black text-gray-900 uppercase tracking-wide">Alamat Kantor</h3>
					<p class="mt-2 text-sm text-gray-600 leading-relaxed">Pergudangan Panam Bizpark No.B-19, Jl. Raya Pekanbaru - Bangkinang, Tuah Madani, Pekanbaru, Riau.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="pb-20">
		<div class="max-w-7xl mx-auto px-6 lg:px-12">
			<div class="grid grid-cols-1 xl:grid-cols-5 gap-8">
				<div class="xl:col-span-3 rounded-3xl border border-gray-100 bg-white p-6 lg:p-8 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.35)]" id="contact-form">
					<div class="flex items-center justify-between gap-4 mb-6">
						<div>
							<p class="text-xs font-bold uppercase tracking-[0.18em] text-[#B83B3B]">Message Form</p>
							<h2 class="mt-2 text-2xl lg:text-3xl font-black text-gray-900">Kirim Kebutuhan Anda</h2>
						</div>
						<span class="hidden sm:inline-flex rounded-full bg-accent-light px-3 py-1 text-[11px] font-semibold text-[#9A2E2E]">Respon di jam kerja</span>
					</div>

					<form id="apm-contact-form" class="space-y-5" method="POST" action="{{ route('contact.store') }}">
                        @csrf
						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<div>
								<label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
								<input id="name" name="name" type="text" required class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]" placeholder="Nama Anda" value="{{ old('name', auth()->user()->name ?? '') }}">
							</div>
							<div>
								<label for="company" class="block text-sm font-semibold text-gray-700 mb-1.5">Perusahaan</label>
								<input id="company" name="company" type="text" class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]" placeholder="Nama perusahaan" value="{{ old('company', auth()->user()->b2bRegistration->company_name ?? '') }}">
							</div>
						</div>

						<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
							<div>
								<label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
								<input id="email" name="email" type="email" required class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]" placeholder="nama@perusahaan.com" value="{{ old('email', auth()->user()->email ?? '') }}">
							</div>
							<div>
								<label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">No. WhatsApp</label>
								<input id="phone" name="phone" type="tel" required class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]" placeholder="08xxxxxxxxxx" value="{{ old('phone', auth()->user()->phone ?? '') }}">
							</div>
						</div>

						<div>
							<label for="topic" class="block text-sm font-semibold text-gray-700 mb-1.5">Topik</label>
							<select id="topic" name="topic" class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]">
								<option value="">Pilih topik</option>
								<option value="inquiry">Inquiry Produk</option>
								<option value="partnership">Kerja Sama B2B</option>
								<option value="support">Bantuan Order</option>
								<option value="other">Lainnya</option>
							</select>
						</div>

						<div>
							<label for="message" class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan</label>
							<textarea id="message" name="message" rows="5" required class="w-full rounded-xl border-gray-200 focus:border-[#B83B3B] focus:ring-[#B83B3B]" placeholder="Tuliskan kebutuhan Anda secara ringkas dan jelas."></textarea>
							<p class="mt-2 text-xs text-gray-500">Dengan mengirim form ini, tim kami akan menghubungi Anda pada jam operasional.</p>
						</div>

						<div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-1">
							<button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#B83B3B] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-200 transition hover:bg-[#9A2E2E] hover:-translate-y-0.5">
								Kirim Pesan
							</button>
                            <p id="form-feedback" class="text-sm font-medium leading-relaxed hidden sm:max-w-md"></p>
						</div>
					</form>

					@if(session('success'))
					<div id="success-toast" class="fixed left-6 bottom-6 z-50 max-w-sm rounded-2xl border border-emerald-200 bg-white px-5 py-4 shadow-2xl shadow-emerald-900/10">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900">Pesan berhasil dikirim</p>
                                <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ session('success') }}</p>
                            </div>
                            <button type="button" id="toast-close" class="ml-2 text-gray-400 hover:text-gray-600" aria-label="Tutup notifikasi">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
					@endif
				</div>

				<div class="xl:col-span-2 space-y-6">
					<div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-orange-50/40 p-6 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.35)]">
						<p class="text-xs font-bold uppercase tracking-[0.18em] text-[#B83B3B]">WhatsApp Priority</p>
						<h3 class="mt-2 text-xl font-black text-gray-900">Perlu Jawaban Lebih Cepat?</h3>
						<p class="mt-2 text-sm text-gray-600 leading-relaxed">Gunakan WhatsApp Business kami untuk kebutuhan mendesak terkait ketersediaan produk, pengiriman, atau kerja sama.</p>
						<a href="https://wa.me/6285274331328?text=Halo%20Tim%20APM,%20saya%20ingin%20konsultasi%20kebutuhan%20produk." target="_blank" rel="noopener" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-600">
							Chat via WhatsApp
						</a>
					</div>

					<div class="rounded-3xl border border-gray-100 bg-white p-3 shadow-[0_20px_50px_-30px_rgba(0,0,0,0.35)]">
						<div class="overflow-hidden rounded-2xl border border-gray-100">
							<iframe
								title="Lokasi PT Agro Pangan Maju"
								src="https://maps.google.com/maps?ll=0.4635959,101.3647667&q=PT%20Agro%20Pangan%20Maju%2C%20Pekanbaru&z=17&output=embed"
								class="h-72 w-full"
								loading="lazy"
								referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
						<div class="px-2 pb-2 pt-4">
							<h4 class="text-sm font-black text-gray-900">Lokasi Operasional</h4>
							<p class="mt-1 text-sm text-gray-600">Pergudangan Panam Bizpark No.B-19, Pekanbaru, Riau.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	@include('partials._footer')

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const form = document.getElementById('apm-contact-form');
			const feedback = document.getElementById('form-feedback');
			const toast = document.getElementById('success-toast');
			const toastClose = document.getElementById('toast-close');

			if (toastClose && toast) {
				toastClose.addEventListener('click', function () {
					toast.remove();
				});

				setTimeout(function () {
					toast.classList.add('opacity-0', 'translate-y-2');
					setTimeout(function () {
						if (toast && toast.parentNode) toast.remove();
					}, 200);
				}, 4500);
			}

			if (!form || !feedback) return;

			@if($errors->any())
				feedback.textContent = @json($errors->first());
				feedback.classList.remove('hidden', 'text-emerald-600');
				feedback.classList.add('text-red-600');
			@endif
		});
	</script>
</body>
</html>
