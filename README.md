# PT. Agro Pangan Maju (APM) - E-Commerce & SCM System

Sistem **B2B & B2C E-Commerce** terintegrasi yang dibangun menggunakan **Laravel 12** dan **TailwindCSS** untuk mengelola pemesanan, stok, dan pengiriman rantai dingin (Cold Chain) untuk komoditas Meat, Poultry, dan Seafood.

---

## 🚀 Fitur Utama & Progress Terakhir

### 1. Hybrid Shipping System (Lalamove & Ekspedisi)
Sistem otomatis yang mampu mendeteksi logika pengiriman jarak jauh maupun lokal berbasis rute Geolocation (*Haversine Formula*).
- **Lalamove Integration (Lokal < 70 KM):** Terintegrasi langsung dengan API Lalamove untuk Quotation harga dinamis dan Dispatch Driver otomatis lewat panel Admin.
- **Pihak Ketiga (Ekspedisi Luar Kota):** Menggunakan RajaOngkir / Komerce untuk lokasi lebih dari 70 KM dari letak koordinat gudang.
- **Live Status & Webhook:** Mendukung pembaruan status driver via Lalamove Webhook URL (`/lalamove/webhook`).

### 2. E-Commerce & Payment Gateway
- **Midtrans Snap:** Proses pembayaran otomatis berbasis *Payment Gateway* yang aman. Memperbarui status pembayaran secara instan menjadi *Paid / Settlement*.
- **Keranjang Dinamis:** Mengkalkulasi otomatis berdasarkan parameter **Quantity (pcs) × Weight (KG/unit)** untuk mengestimasi total beban berat paket pesanan (KG).

### 3. Alur Konfirmasi Pesanan (User & Admin)
Mekanisme pengamanan sistem sebelum transaksi dapat diselesaikan:
- **Pembeli:** Terdapat fitur *"Konfirmasi Pesanan Diterima"* di halaman User Dashboard ketika admin telah menandai produk "Dikirim".
- **Admin:** Tombol "Tandai Pesanan Selesai" di panel *Admin CMS* akan aktif/disugestikan penuh **hanya setelah** pelanggan mengkonfirmasi terima barang atau telah disupervisi langsung oleh admin yang berwenang.

### 4. Admin Management (CMS)
- Fitur visualisasi Manajemen Pesanan (*Color-coded badge*) yang membedakan alur pesanan Lalamove (Biru) dan Ekspedisi (Oranye).
- Update nomor resi otomatis (resi JNE/SiCepat) atau "Panggil Driver Lalamove".
- Fitur otomatis **Force Restore Stock** saat transaksi yang sudah dibayar (*paid*) sengaja di-cancel/dibatalkan untuk menjaga integritas gudang.

### 5. UI/UX Polishing
- Implementasi desain responsif, modern, berbasis TailwindCSS.
- Telah membersihkan struktur Website dari route/halaman tak terpakai (contoh: Blog & Career & Struktur Organisasi).
- Invoice auto-generated.

---

## 🛠️ Stack Teknologi
- **Framework:** Laravel 12.x (PHP 8.2+)
- **Frontend:** Blade Templating, TailwindCSS, AlpineJS (Optional)
- **Map & Geocoding:** Leaflet.js
- **Database:** MySQL / PostgreSQL
- **Integrations:** Lalamove API (REST), Midtrans API, RajaOngkir API

---

## 📦 Panduan Instalasi (Development)

1. **Clone & Install Dependencies**
   ```bash
   git clone [repository-url]
   cd website-apm
   composer install
   npm install
   ```

2. **Environment variables**
   Copy `.env.example` ke `.env` lalu sesuaikan konfigurasi di bawah ini:
   ```env
   # MIDTRANS
   MIDTRANS_SERVER_KEY=
   MIDTRANS_CLIENT_KEY=

   # LALAMOVE
   LALAMOVE_API_KEY=
   LALAMOVE_API_SECRET=
   LALAMOVE_IS_SANDBOX=true
   LALAMOVE_WAREHOUSE_LAT=0.5071
   LALAMOVE_WAREHOUSE_LNG=101.4478

   # RAJAONGKIR
   RAJAONGKIR_API_KEY=
   ```

3. **Migrate Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Jalankan Aplikasi**
   Termial 1: `php artisan serve`  
   Terminal 2: `npm run dev`

---

*Dikembangkan untuk PT. Agro Pangan Maju.*
