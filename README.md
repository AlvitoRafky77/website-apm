# PT. Agro Pangan Maju (APM) - E-Commerce & SCM System

Sistem **B2B & B2C E-Commerce** terintegrasi untuk PT. Agro Pangan Maju yang dibangun menggunakan **Laravel 12** dan **TailwindCSS**. Aplikasi ini berfokus pada pengelolaan pemesanan, stok, pembayaran, pengiriman, dan administrasi rantai pasok untuk komoditas **Meat, Poultry, dan Seafood**.

---

## Gambaran Umum Sistem

Website ini dirancang sebagai platform operasional perusahaan untuk melayani beberapa kebutuhan utama:

- **B2C (Retail):** pembeli eceran dapat melihat katalog produk, melakukan checkout, dan membayar pesanan secara online.
- **B2B (Mitra Bisnis):** mitra bisnis dapat melakukan registrasi, mendapatkan persetujuan admin, melihat harga grosir, dan memproses pesanan dalam alur bisnis yang berbeda.
- **Admin CMS:** admin dapat mengelola produk, stok, pesanan, pengiriman, pembayaran, kontak masuk, dan data operasional lainnya.
- **Hybrid Shipping:** sistem pengiriman mendukung **Lalamove** untuk pengiriman lokal dan **RajaOngkir** untuk pengiriman eceran/regional sesuai konfigurasi yang tersedia.
- **Payment Gateway:** integrasi **Midtrans** digunakan untuk transaksi pembayaran online.

---

## Fitur Utama

### 1. Authentication & Role Management
- Login, registrasi, dan manajemen akun pengguna.
- Pembagian peran untuk:
  - **Administrator**
  - **Pembeli Eceran**
  - **Mitra Bisnis**
- Akses halaman dan fitur disesuaikan berdasarkan role pengguna.

### 2. B2C Flow
- Katalog produk untuk pembeli umum/eceran.
- Keranjang belanja dan checkout.
- Perhitungan pesanan berdasarkan kuantitas dan komposisi produk.
- Integrasi pembayaran melalui Midtrans.
- Alur konfirmasi pesanan dan status pengiriman.

### 3. B2B Flow
- Form registrasi mitra bisnis dengan proses persetujuan admin.
- Harga grosir untuk user B2B yang sudah disetujui.
- Alur pesanan khusus B2B.
- Dukungan shipping khusus untuk pengiriman bisnis, termasuk integrasi Lalamove.

### 4. Product & Inventory Management
- Manajemen produk dengan kategori.
- Pengaturan harga eceran dan grosir.
- Pengelolaan stok gudang.
- Stock adjustment / stock opname.
- Perlindungan integritas stok ketika pesanan dibatalkan atau status pembayaran berubah.

### 5. Order & Shipping Management
- Manajemen pesanan dari sisi user dan admin.
- Status pesanan yang lebih detail untuk proses operasional.
- Integrasi pengiriman lokal dan regional.
- Webhook / update status pengiriman untuk layanan yang didukung.

### 6. Payment Integration
- Integrasi **Midtrans** untuk pembayaran online.
- Pencatatan status pembayaran dan metadata transaksi.
- Dukungan alur pembayaran yang lebih aman dan terpantau.

### 7. Chatbot & Contact Handling
- Chatbot untuk menjawab pertanyaan umum pengguna.
- Pengelolaan skenario jawaban oleh admin.
- Form/fitur kontak masuk untuk menerima pesan pengguna.

### 8. Admin Dashboard & Operational Tools
- Dashboard admin untuk memantau data operasional.
- Pengelolaan produk, kategori, pesanan, pelanggan, dan pengiriman.
- Pengelolaan data keuangan / sinkronisasi finansial.
- Tampilan yang mendukung operasional harian tim internal.

---

## Stack Teknologi

- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Frontend:** Blade Templates, TailwindCSS, AlpineJS
- **Database:** MySQL
- **Payment Gateway:** Midtrans API
- **Shipping / Logistics:** Lalamove API, RajaOngkir API
- **Supporting Libraries:** Vite, Laravel Breeze / auth scaffolding sesuai implementasi proyek

---

## Struktur Proyek Singkat

- `app/Http/Controllers/` - controller untuk admin, auth, B2B, B2C, checkout, chatbot, contact, product, order, dan profile
- `app/Models/` - model utama seperti `User`, `Product`, `Order`, `B2bOrder`, `B2bRegistration`, `Category`, dan lain-lain
- `app/Services/` - service layer untuk integrasi eksternal dan logika bisnis
- `config/` - konfigurasi layanan seperti `midtrans.php`, `lalamove.php`, `rajaongkir.php`
- `database/migrations/` - skema database untuk user, produk, pesanan, B2B, pembayaran, dan contact messages
- `resources/views/` - tampilan Blade untuk landing page, auth, admin, checkout, orders, products, profile, dan halaman publik
- `routes/web.php` - routing utama website
- `routes/auth.php` - routing autentikasi

---

## Fitur yang Tersedia di Website

Beberapa komponen utama yang sudah ada di project ini:

- Landing page dan halaman publik
- Katalog produk
- Login / registrasi
- Halaman admin
- Halaman B2B
- Checkout dan order flow
- Pengelolaan pengiriman
- Chatbot
- Contact message handling
- Manajemen stok dan produk
- Dukungan invoice / dokumen transaksi

---

## Alur Sistem Singkat

### B2C
1. Pengunjung melihat katalog produk.
2. User login atau register sebagai pembeli.
3. User menambahkan produk ke keranjang.
4. User checkout dan memilih metode pembayaran/pengiriman.
5. Admin memproses pesanan dan update status.

### B2B
1. Mitra bisnis mendaftar melalui formulir registrasi.
2. Admin meninjau dan menyetujui pendaftaran.
3. Mitra bisnis mendapatkan akses ke harga grosir dan alur pesanan B2B.
4. Sistem memproses order dan pengiriman sesuai aturan bisnis yang berlaku.

### Admin
1. Admin login ke dashboard.
2. Admin mengelola produk, stok, kategori, pesanan, dan registrasi B2B.
3. Admin memantau pembayaran, pengiriman, dan data operasional lainnya.
4. Admin dapat menangani pembaruan status pesanan dan integrasi layanan eksternal.

---

## Instalasi & Menjalankan Project

### 1. Clone repository
```bash
git clone [repository-url]
cd website-apm
```

### 2. Install dependency
```bash
composer install
npm install
```

### 3. Salin environment file
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi `.env`
Sesuaikan credential layanan berikut sesuai environment yang digunakan:

```env
APP_NAME="PT Agro Pangan Maju"
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=website_apm
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=

LALAMOVE_API_KEY=
LALAMOVE_API_SECRET=
LALAMOVE_IS_SANDBOX=true
LALAMOVE_WAREHOUSE_LAT=
LALAMOVE_WAREHOUSE_LNG=

RAJAONGKIR_API_KEY=
```

### 5. Migrasi database dan seed
```bash
php artisan migrate --seed
```

### 6. Jalankan aplikasi
Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

---

## Catatan Pengembangan

- Proyek ini menggunakan struktur Laravel modern dengan pemisahan controller, model, service, dan view.
- Integrasi eksternal dipusatkan di folder `app/Services`.
- Konfigurasi layanan dipisahkan agar mudah diubah antara mode sandbox dan production.
- Sistem mendukung kebutuhan operasional perusahaan yang memadukan penjualan eceran dan mitra bisnis.

---

## Lisensi

Proyek ini dikembangkan untuk kebutuhan internal **PT. Agro Pangan Maju**.
