<x-app-layout>
    <title>Checkout — PT. Agro Pangan Maju</title>

    @push('scripts')
    {{-- Midtrans Snap.js --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    {{-- Leaflet (OpenStreetMap) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const CSRF_TOKEN          = '{{ csrf_token() }}';
        const CART_UPDATE_URL     = '{{ route('cart.update') }}';
        const CART_REMOVE_URL     = '{{ route('cart.remove') }}';
        const CHECKOUT_URL        = '{{ route('checkout.place') }}';
        const SHIPPING_QUOTE_URL  = '{{ route('checkout.shipping.quote') }}';
        const WAREHOUSE_LAT       = {{ config('lalamove.warehouse.lat') }};
        const WAREHOUSE_LNG       = {{ config('lalamove.warehouse.lng') }};
        const USER_CITY           = '{{ auth()->user()->city ?? '' }}';

        // ── Helpers ──────────────────────────────────────────────
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(number));
        }

        function updateCartBadge(count) {
            document.querySelectorAll('[data-cart-badge]').forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? 'flex' : 'none';
            });
        }

        function showCartMessage(message, type = 'error') {
            let box = document.getElementById('cartMessageBox');
            if (!box) {
                box = document.createElement('div');
                box.id = 'cartMessageBox';
                box.className = 'fixed top-4 right-4 z-[99999] max-w-sm rounded-xl px-4 py-3 text-sm font-medium shadow-lg border transition-opacity duration-200';
                document.body.appendChild(box);
            }

            const styles = type === 'success'
                ? 'bg-green-50 border-green-200 text-green-700'
                : 'bg-red-50 border-red-200 text-red-700';

            box.className = `fixed top-4 right-4 z-[99999] max-w-sm rounded-xl px-4 py-3 text-sm font-medium shadow-lg border transition-opacity duration-200 ${styles}`;
            box.textContent = message;
            box.style.opacity = '1';

            clearTimeout(box._hideTimer);
            box._hideTimer = setTimeout(() => {
                box.style.opacity = '0';
            }, 3500);
        }

        // ── Subtotal & Total recalculation ───────────────────────
        let currentShipping = {{ $shipping }};

        function recalcTotal() {
            let subtotal = 0;
            document.querySelectorAll('[data-item-row]').forEach(row => {
                const price = parseFloat(row.dataset.price);
                const qty   = parseInt(row.querySelector('[data-qty-input]').value);
                const lineEl = row.querySelector('[data-line-total]');
                lineEl.textContent = formatRupiah(price * qty);
                subtotal += price * qty;
            });
            document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
            document.getElementById('shippingDisplay').textContent = currentShipping > 0
                ? formatRupiah(currentShipping)
                : '—';
            document.getElementById('totalDisplay').textContent = formatRupiah(subtotal + currentShipping);
        }

        // ── Qty controls ─────────────────────────────────────────
        function changeQty(productId, delta) {
            const row   = document.querySelector(`[data-item-row][data-product-id="${productId}"]`);
            const input = row.querySelector('[data-qty-input]');
            const max   = parseInt(input.max);
            const min   = parseInt(input.min) || 1;
            const currentQty = parseInt(input.value) || min;
            const newQty  = Math.max(min, Math.min(max, currentQty + delta));
            input.value = newQty;

            fetch(CART_UPDATE_URL, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN},
                body: JSON.stringify({product_id: productId, quantity: newQty})
            })
            .then(async r => {
                const data = await r.json().catch(() => ({}));

                if (!r.ok) {
                    throw new Error(data.error || data.message || 'Gagal memperbarui kuantitas.');
                }

                return data;
            })
            .then(data => {
                if (data.success) {
                    recalcTotal();
                    updateCartBadge(data.cart_count);
                    if (document.getElementById('recipient_lat').value) {
                        fetchShippingQuote();
                    }
                }
            })
            .catch(err => {
                input.value = currentQty;
                showCartMessage(err.message || 'Gagal memperbarui kuantitas.');
            });
        }

        function removeItem(productId) {
            fetch(CART_REMOVE_URL, {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN},
                body: JSON.stringify({product_id: productId})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`[data-item-row][data-product-id="${productId}"]`);
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        row.remove();
                        recalcTotal();
                        updateCartBadge(data.cart_count);
                        if (document.querySelectorAll('[data-item-row]').length === 0) {
                            window.location.href = '{{ route('products.index') }}';
                        }
                    }, 300);
                }
            });
        }

        // ── Map & Address Autocomplete (OpenStreetMap + Nominatim) ──
        let map, marker, shippingQuoteTimer;
        let selectedLat = null, selectedLng = null;

        function getRecipientCity() {
            const cityInput = document.getElementById('recipient_city');
            return cityInput ? cityInput.value.trim() : '';
        }

        function resolveCityFromNominatim(address = {}) {
            return (
                address.city ||
                address.town ||
                address.municipality ||
                address.county ||
                address.city_district ||
                address.state_district ||
                USER_CITY ||
                ''
            ).trim();
        }

        function initMap() {
            // Initialize Leaflet map centered on Pekanbaru
            map = L.map('delivery-map').setView([0.5071, 101.4478], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Warehouse marker (origin)
            const warehouseIcon = L.divIcon({
                html: `<div class="flex items-center justify-center w-8 h-8 bg-[#CD422B] text-white rounded-full shadow-lg text-sm font-bold border-2 border-white">A</div>`,
                className: '',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });
            L.marker([WAREHOUSE_LAT, WAREHOUSE_LNG], {icon: warehouseIcon})
                .addTo(map)
                .bindPopup('<strong>📦 Gudang APM</strong><br>Panam Bizpark, Pekanbaru');

            // Click on map to set destination
            map.on('click', function(e) {
                setDestinationPin(e.latlng.lat, e.latlng.lng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        }

        function setDestinationPin(lat, lng) {
            selectedLat = lat;
            selectedLng = lng;

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                const destIcon = L.divIcon({
                    html: `<div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full shadow-lg text-sm font-bold border-2 border-white">B</div>`,
                    className: '',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                });
                marker = L.marker([lat, lng], {icon: destIcon, draggable: true}).addTo(map);
                marker.on('dragend', function() {
                    const pos = marker.getLatLng();
                    selectedLat = pos.lat;
                    selectedLng = pos.lng;
                    reverseGeocode(pos.lat, pos.lng);
                    scheduleShippingQuote();
                });
            }

            // Update hidden fields
            document.getElementById('recipient_lat').value = lat.toFixed(7);
            document.getElementById('recipient_lng').value = lng.toFixed(7);

            scheduleShippingQuote();
        }

        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`)
                .then(r => r.json())
                .then(data => {
                    if (data.display_name) {
                        const addressInput = document.getElementById('recipient_address');
                        if (!addressInput.dataset.manuallyEdited) {
                            addressInput.value = data.display_name;
                        }
                    }

                    const cityInput = document.getElementById('recipient_city');
                    if (cityInput) {
                        cityInput.value = resolveCityFromNominatim(data.address || {});
                    }
                })
                .catch(() => {});
        }

        function searchAddress() {
            const query = document.getElementById('address-search').value;
            if (!query || query.length < 3) return;

            const suggestions = document.getElementById('address-suggestions');
            suggestions.innerHTML = '<div class="px-3 py-2 text-xs text-gray-400">Mencari...</div>';
            suggestions.classList.remove('hidden');

            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5&countrycodes=id`)
                .then(r => r.json())
                .then(results => {
                    if (!results.length) {
                        suggestions.innerHTML = '<div class="px-3 py-2 text-xs text-gray-400">Tidak ditemukan</div>';
                        return;
                    }

                    suggestions.innerHTML = results.map(r => {
                        const city = resolveCityFromNominatim(r.address || {});

                        return `
                        <div class="px-3 py-2 text-xs text-gray-800 cursor-pointer hover:bg-gray-50 border-b border-gray-50 last:border-0"
                             onclick="selectAddress('${r.lat}', '${r.lon}', \`${r.display_name.replace(/`/g, '')}\`, \`${city.replace(/`/g, '')}\`)">
                            <span class="font-medium">${r.display_name.split(',')[0]}</span>
                            <span class="text-gray-400 block">${r.display_name.split(',').slice(1, 3).join(',')}</span>
                        </div>
                    `;
                    }).join('');
                });
        }

        function selectAddress(lat, lng, name, city = '') {
            document.getElementById('address-search').value = name.split(',').slice(0, 2).join(',').trim();
            document.getElementById('address-suggestions').classList.add('hidden');
            document.getElementById('recipient_address').value = name;

            const cityInput = document.getElementById('recipient_city');
            if (cityInput) {
                cityInput.value = (city || USER_CITY || '').trim();
            }

            map.setView([parseFloat(lat), parseFloat(lng)], 15);
            setDestinationPin(parseFloat(lat), parseFloat(lng));
        }

        function scheduleShippingQuote() {
            clearTimeout(shippingQuoteTimer);
            shippingQuoteTimer = setTimeout(fetchShippingQuote, 800);
        }

        function fetchShippingQuote(cityOverride = null) {
            if (!selectedLat || !selectedLng) return;

            const shippingBox = document.getElementById('shipping-info');
            shippingBox.innerHTML = `
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Menghitung ongkir...
                </div>`;

            const city = cityOverride || getRecipientCity() || '';

            fetch(SHIPPING_QUOTE_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN},
                body: JSON.stringify({
                    lat:     selectedLat,
                    lng:     selectedLng,
                    address: document.getElementById('recipient_address').value,
                    city:    city,
                })
            })
            .then(r => r.json())
            .then(data => {
                // ── LALAMOVE (dalam kota) ──────────────────────────────
                if (data.shipping_mode === 'lalamove' && data.success) {
                    currentShipping = data.price;
                    document.getElementById('shipping_cost_input').value      = data.price;
                    document.getElementById('shipping_vehicle_input').value   = data.vehicle_type;
                    document.getElementById('lalamove_quotation_id_input').value = data.quotation_id;
                    document.getElementById('shipping_mode_input').value      = 'lalamove';
                    document.getElementById('shipping_courier_input').value   = '';

                    shippingBox.innerHTML = `
                        <div class="space-y-2">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full w-fit">
                                🏍️ Pengiriman Lalamove — Dalam Kota (${data.distance_km} KM)
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">${data.vehicle_icon}</span>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">${data.vehicle_label} <span class="text-xs text-gray-400">(Lalamove)</span></p>
                                        <p class="text-xs text-gray-500">± ${data.distance_km} KM · Est. ${data.estimate_mins} menit</p>
                                    </div>
                                </div>
                                <span class="font-black text-[#CD422B] text-base">${formatRupiah(data.price)}</span>
                            </div>
                        </div>`;
                    recalcTotal();
                    return;
                }

                // ── RAJAONGKIR (luar kota) — butuh input kota ──────────
                if (data.shipping_mode === 'rajaongkir' && data.need_city) {
                    shippingBox.innerHTML = `
                        <div class="space-y-3">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full w-fit">
                                🚚 Pengiriman Ekspedisi — Luar Kota (${data.distance_km} KM dari gudang)
                            </div>
                            <p class="text-xs text-gray-500">Lokasi berada di luar jangkauan Lalamove. Sistem akan memakai kota dari alamat yang dipilih untuk menghitung tarif ekspedisi.</p>
                        </div>`;
                    document.getElementById('shipping_mode_input').value = 'rajaongkir';
                    currentShipping = 0;
                    recalcTotal();
                    return;
                }

                // ── RAJAONGKIR — tampilkan pilihan kurir ───────────────
                if (data.shipping_mode === 'rajaongkir' && data.options) {
                    document.getElementById('shipping_mode_input').value = 'rajaongkir';

                    const opts = data.options.slice(0, 6); // max 6 pilihan
                    const optHTML = opts.map((o, i) => `
                        <label class="flex items-center justify-between p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50 transition ${i === 0 ? 'border-[#CD422B] bg-red-50/50' : 'border-gray-200'}">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="courier_choice" value="${o.service}"
                                       data-price="${o.price}" data-label="${o.label}" data-courier="${o.courier}"
                                       ${i === 0 ? 'checked' : ''}
                                       onchange="selectCourier(this)"
                                       class="accent-[#CD422B]">
                                <div>
                                    <p class="text-xs font-bold text-gray-800">${o.label}</p>
                                    <p class="text-[10px] text-gray-400">Est. ${o.etd}</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-[#CD422B]">${formatRupiah(o.price)}</span>
                        </label>
                    `).join('');

                    shippingBox.innerHTML = `
                        <div class="space-y-2">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full w-fit">
                                🚚 Pengiriman Ekspedisi — Luar Kota (${data.distance_km} KM)
                            </div>
                            <p class="text-[10px] text-gray-400 mb-1">Pilih layanan pengiriman yang sesuai:</p>
                            <div class="space-y-1.5">${optHTML}</div>
                        </div>`;

                    // Auto-pilih yang pertama
                    if (opts.length > 0) {
                        currentShipping = opts[0].price;
                        document.getElementById('shipping_cost_input').value    = opts[0].price;
                        document.getElementById('shipping_vehicle_input').value = opts[0].service;
                        document.getElementById('shipping_courier_input').value = opts[0].courier;
                        recalcTotal();
                    }
                    return;
                }

                // Error
                shippingBox.innerHTML = `<p class="text-sm text-red-500">${data.message || 'Gagal menghitung ongkir. Coba lagi.'}</p>`;
            })
            .catch(() => {
                shippingBox.innerHTML = `<p class="text-sm text-red-500">Gagal terhubung ke server. Coba lagi.</p>`;
            });
        }

        function selectCourier(el) {
            currentShipping = parseInt(el.dataset.price);
            document.getElementById('shipping_cost_input').value    = el.dataset.price;
            document.getElementById('shipping_vehicle_input').value = el.value;
            document.getElementById('shipping_courier_input').value = el.dataset.courier;
            // Update border
            document.querySelectorAll('[name="courier_choice"]').forEach(r => {
                r.closest('label').classList.toggle('border-[#CD422B]', r.checked);
                r.closest('label').classList.toggle('bg-red-50/50', r.checked);
                r.closest('label').classList.toggle('border-gray-200', !r.checked);
            });
            recalcTotal();
        }

        // ── Proceed to Payment ────────────────────────────────────
        function showPaymentSuccessPopup(orderId, totalPaid, paymentType) {
            let popup = document.getElementById('paymentSuccessPopup');
            if (!popup) {
                popup = document.createElement('div');
                popup.id = 'paymentSuccessPopup';
                popup.className = 'fixed inset-0 z-[99999] hidden items-center justify-center bg-black/50 px-4';
                popup.innerHTML = `
                    <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl overflow-hidden border border-gray-100">
                        <div class="p-8 text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil! 🎉</h2>
                            <p class="text-sm text-gray-500 leading-relaxed mb-6">Pesanan Anda sudah berhasil dibayar dan sedang diproses oleh tim kami.</p>
                            <div class="bg-gray-50 rounded-2xl p-5 text-left space-y-3 border border-gray-100 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">ID Pesanan</span>
                                    <span class="font-bold text-gray-900 font-mono" id="popupOrderId"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Total Dibayar</span>
                                    <span class="font-bold text-[#CD422B] text-base" id="popupTotalPaid"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Metode</span>
                                    <span class="font-semibold text-gray-700 capitalize" id="popupPaymentType"></span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold px-6 py-3 rounded-xl hover:shadow-lg transition">
                                    Kembali ke Beranda
                                </a>
                                <button type="button" onclick="closePaymentSuccessPopup()" class="inline-flex items-center justify-center gap-2 bg-white border-2 border-gray-200 text-gray-700 font-bold px-6 py-3 rounded-xl hover:border-[#CD422B] hover:text-[#CD422B] transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>`;
                document.body.appendChild(popup);
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) closePaymentSuccessPopup();
                });
            }

            document.getElementById('popupOrderId').textContent = orderId || '-';
            document.getElementById('popupTotalPaid').textContent = formatRupiah(totalPaid || 0);
            document.getElementById('popupPaymentType').textContent = (paymentType || 'midtrans').replace(/_/g, ' ');
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function syncPaidOrder(orderId) {
            const url = '{{ route('checkout.finish') }}?order_id=' + encodeURIComponent(orderId);

            // Fire-and-forget sync so the success popup is not blocked by the status refresh.
            fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            }).catch(() => {});

            return Promise.resolve(true);
        }

        function extractPaymentData(result, data) {
            const grossAmount = Number(
                result?.gross_amount ??
                result?.transaction_amount ??
                result?.amount ??
                data?.total ??
                0
            );
            const paymentType = (
                result?.payment_type ??
                result?.va_numbers?.[0]?.bank ??
                result?.permata_va_number ??
                result?.store ??
                result?.acquirer ??
                data?.payment_type ??
                'midtrans'
            );
            return { grossAmount: grossAmount, paymentType: paymentType };
        }

        function closePaymentSuccessPopup() {
            const popup = document.getElementById('paymentSuccessPopup');
            if (!popup) return;
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        function resetPayBtn(btn) {
            if (!btn) return;
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Sekarang`;
        }

        function proceedToPayment() {
            const form = document.getElementById('checkoutForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!selectedLat || !selectedLng) {
                alert('Silakan pilih lokasi pengiriman di peta terlebih dahulu.');
                return;
            }

            if (!document.getElementById('shipping_cost_input').value) {
                alert('Ongkos kirim belum terhitung. Pastikan lokasi sudah dipilih di peta.');
                return;
            }

            const btn = document.getElementById('payBtn');
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Memproses...`;

            const formData = new FormData(form);

            fetch(CHECKOUT_URL, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json'},
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            const paymentData = extractPaymentData(result, data);
                            syncPaidOrder(data.order_id);
                            resetPayBtn(btn);
                            showPaymentSuccessPopup(data.order_id, paymentData.grossAmount, paymentData.paymentType);
                        },
                        onPending: function(result) {
                            const paymentData = extractPaymentData(result, data);
                            syncPaidOrder(data.order_id);
                            resetPayBtn(btn);
                            showPaymentSuccessPopup(data.order_id, paymentData.grossAmount, paymentData.paymentType);
                        },
                        onError: function() {
                            resetPayBtn(btn);
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: function() {
                            resetPayBtn(btn);
                        },
                    });
                } else {
                    alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    resetPayBtn(btn);
                }
            })
            .catch(() => {
                alert('Gagal terhubung ke server. Coba lagi.');
                resetPayBtn(btn);
            });
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            initMap();

            // Debounce address search
            let searchTimer;
            document.getElementById('address-search').addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(searchAddress, 500);
            });

            // Close suggestions on click outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('#address-search-wrapper')) {
                    document.getElementById('address-suggestions').classList.add('hidden');
                }
            });

            // Mark textarea as manually edited
            document.getElementById('recipient_address').addEventListener('input', function() {
                this.dataset.manuallyEdited = '1';
            });
        });
    </script>
    @endpush

    <style>
        #delivery-map { height: 280px; border-radius: 16px; z-index: 1; }
        .leaflet-container { border-radius: 16px; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pb-20">
        {{-- Breadcrumb --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-[#CD422B] transition font-medium">Home</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('products.index') }}" class="hover:text-[#CD422B] transition font-medium">Produk</a>
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="font-bold text-gray-800">Checkout</span>
            </nav>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Ringkasan Pesanan</h1>
                <p class="text-gray-500 mt-1 text-sm">Periksa pesanan, atur lokasi, dan konfirmasi pembayaran</p>
            </div>

            <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
                @csrf
                {{-- Hidden Fields --}}
                <input type="hidden" id="recipient_city" name="recipient_city" value="{{ old('recipient_city', auth()->user()->city ?? '') }}">
                <input type="hidden" id="recipient_lat" name="recipient_lat" value="">
                <input type="hidden" id="recipient_lng" name="recipient_lng" value="">
                <input type="hidden" id="shipping_cost_input" name="shipping_cost" value="">
                <input type="hidden" id="shipping_vehicle_input" name="shipping_vehicle" value="">
                <input type="hidden" id="shipping_mode_input" name="shipping_mode" value="lalamove">
                <input type="hidden" id="shipping_courier_input" name="shipping_courier" value="">
                <input type="hidden" id="lalamove_quotation_id_input" name="lalamove_quotation_id" value="">

                <div class="flex flex-col lg:flex-row gap-8 items-start">

                    {{-- ====== LEFT COLUMN ====== --}}
                    <div class="w-full lg:w-7/12 space-y-5">

                        {{-- Cart Items --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                                <h2 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Keranjang Belanja
                                    <span class="text-xs text-gray-400 font-normal">({{ count($cart) }} produk · Total {{ number_format($totalWeight, 1) }} KG)</span>
                                </h2>
                                <a href="{{ route('products.index') }}" class="text-xs text-[#CD422B] font-semibold hover:underline">+ Tambah Produk</a>
                            </div>

                            <div class="divide-y divide-gray-50 px-2">
                                @foreach($cart as $item)
                                <div class="flex items-start gap-4 p-4 hover:bg-gray-50/60 transition rounded-xl"
                                     data-item-row
                                     data-product-id="{{ $item['product_id'] }}"
                                     data-price="{{ $item['price'] }}">

                                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-100">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $item['name'] }}</h3>
                                        <p class="text-xs text-gray-400 mt-0.5">Berat: {{ $item['weight'] ?? 1 }} KG/unit · Maks. {{ $item['max_order'] }} KG</p>
                                        <p class="text-sm font-bold text-[#CD422B] mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>

                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                            <button type="button" onclick="changeQty({{ $item['product_id'] }}, -1)"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#CD422B] hover:bg-red-50 transition font-bold text-base">−</button>
                                              <input type="number" value="{{ $item['quantity'] }}" min="10" max="{{ $item['max_order'] }}"
                                                   data-qty-input
                                                   style="-webkit-appearance:none;-moz-appearance:textfield;"
                                                   class="w-16 min-w-[4rem] h-8 text-center text-sm font-bold text-gray-800 border-x border-gray-200 bg-white focus:outline-none tabular-nums px-1"
                                                  onchange="changeQty({{ $item['product_id'] }}, 0); this.value = Math.max(10, Math.min({{ $item['max_order'] }}, parseInt(this.value)||10));">
                                            <button type="button" onclick="changeQty({{ $item['product_id'] }}, 1)"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#CD422B] hover:bg-red-50 transition font-bold text-base">+</button>
                                        </div>
                                        <p class="font-bold text-gray-900 text-sm" data-line-total>
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </p>
                                        <button type="button" onclick="removeItem({{ $item['product_id'] }})"
                                                class="text-xs text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-full transition flex items-center gap-1 font-semibold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Delivery Info Form --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 class="font-bold text-gray-900 text-base flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Informasi Penerima
                            </h2>

                            @if(auth()->user()->phone || auth()->user()->address)
                            <div class="flex items-center gap-2 bg-green-50 border border-green-100 rounded-xl px-4 py-2.5 mb-4">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-xs text-green-700">Data diisi otomatis dari profil Anda. Anda tetap bisa mengubahnya.</p>
                            </div>
                            @endif

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                                        <input type="text" name="recipient_name"
                                               value="{{ old('recipient_name', auth()->user()->name ?? '') }}"
                                               required placeholder="Nama lengkap penerima"
                                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="text" name="recipient_phone"
                                               value="{{ old('recipient_phone', auth()->user()->phone ?? '') }}"
                                               required placeholder="08xx-xxxx-xxxx"
                                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan Pesanan (opsional)</label>
                                    <textarea name="notes" rows="2"
                                              placeholder="Instruksi khusus, waktu pengiriman, dll."
                                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition resize-none">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Map & Address --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 class="font-bold text-gray-900 text-base flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 16l4.553 2.276A1 1 0 0021 24.382V8.618a1 1 0 00-.553-.894L15 5m0 15V5"/></svg>
                                Lokasi Pengiriman
                            </h2>

                            {{-- Address Search --}}
                            <div id="address-search-wrapper" class="relative mb-3">
                                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-[#CD422B]/30 focus-within:border-[#CD422B] transition">
                                    <svg class="w-4 h-4 text-gray-400 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" id="address-search"
                                           placeholder="Cari alamat pengiriman... (atau klik langsung di peta)"
                                           class="flex-1 px-3 py-3 text-sm focus:outline-none bg-transparent">
                                </div>
                                <div id="address-suggestions"
                                     class="hidden absolute top-full left-0 right-0 z-50 bg-white border border-gray-200 rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                                </div>
                            </div>

                            {{-- Leaflet Map --}}
                            <div id="delivery-map" class="w-full border border-gray-100 shadow-sm"></div>

                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cari alamat lewat kolom di atas atau klik langsung di peta untuk menentukan titik antar.
                            </p>

                            {{-- Alamat Lengkap --}}
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea id="recipient_address" name="recipient_address" rows="3" required
                                          placeholder="Terisi otomatis saat pilih di peta, atau isi manual..."
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/30 focus:border-[#CD422B] transition resize-none">{{ old('recipient_address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>{{-- end left col --}}

                    {{-- ====== RIGHT COLUMN ====== --}}
                    <div class="w-full lg:w-5/12 lg:sticky lg:top-24 space-y-4">

                        {{-- Payment Summary --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-50">
                                <h2 class="font-bold text-gray-900 text-base">Ringkasan Pembayaran</h2>
                            </div>
                            <div class="px-6 py-5 space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Subtotal ({{ count($cart) }} produk)</span>
                                    <span class="font-semibold text-gray-800" id="subtotalDisplay">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Ongkos Kirim</span>
                                    <span id="shippingDisplay" class="text-amber-600 font-semibold text-xs bg-amber-50 px-2 py-0.5 rounded-full">Pilih lokasi</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Diskon</span>
                                    <span class="text-green-600 font-semibold">—</span>
                                </div>
                                <hr class="border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-black text-xl text-[#CD422B]" id="totalDisplay">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Info Box --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                Layanan Pengiriman
                            </h3>
                            <div id="shipping-info" class="text-sm text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🚚</span>
                                    <p class="text-xs text-gray-400">Pilih lokasi di peta. Dalam kota Pekanbaru → Lalamove otomatis. Luar kota → pilih ekspedisi (JNE/SiCepat/dll).</p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h3 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Metode Pembayaran
                            </h3>
                            <label class="flex items-center gap-3 p-3 border-2 border-[#CD422B] bg-red-50/50 rounded-xl cursor-pointer">
                                <input type="radio" name="payment_method" value="midtrans" checked class="accent-[#CD422B]">
                                <div class="flex items-center gap-2 flex-1">
                                    <div class="bg-[#CD422B] text-white text-[10px] font-bold px-2 py-0.5 rounded">MIDTRANS</div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-xs">Kartu / Transfer / GoPay / QRIS</p>
                                        <p class="text-[10px] text-gray-400">Pembayaran aman via Midtrans</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Info --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 flex items-start gap-3">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Dalam Pekanbaru:</strong> Pengiriman oleh <strong>Lalamove</strong> — driver menjemput dari gudang APM.<br>
                            <strong>Luar Kota/Pulau:</strong> Dikirim via ekspedisi (JNE/SiCepat/dll) — ongkir sudah termasuk dalam pembayaran.
                        </p>
                    </div>

                        {{-- Pay Button --}}
                        <button type="button" id="payBtn" onclick="proceedToPayment()"
                                class="w-full bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2.5 text-base disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Bayar Sekarang
                        </button>

                        <p class="text-center text-xs text-gray-400">
                            Dengan menekan tombol di atas, Anda menyetujui
                            <a href="#" class="text-[#CD422B] hover:underline">Syarat & Ketentuan</a> kami.
                        </p>

                        <div class="flex items-center justify-center gap-4 pt-1">
                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Transaksi Aman
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Data Dilindungi
                            </div>
                        </div>
                    </div>{{-- end right col --}}

                </div>
            </form>
        </div>
    </div>

    @include('partials._footer')
</x-app-layout>
