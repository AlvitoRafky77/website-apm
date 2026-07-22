<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    // ─── Keyword → flow mapping ────────────────────────────
    private array $keywordMap = [
        'welcome'            => ['halo', 'hai', 'hello', 'hi', 'selamat', 'pagi', 'siang', 'sore', 'malam', 'mulai', 'start', 'menu', 'bantuan', 'help', 'helo', 'hallo', 'pigi', 'malem', 'woi', 'infonya', 'assistant', 'bot', 'asisten'],
        'stok_kategori'      => ['stok', 'stock', 'tersedia', 'ada gak', 'ada ga', 'ketersediaan', 'cek stok', 'mau beli', 'mau pesan', 'daging', 'seafood', 'poultry', 'ayam', 'sapi', 'udang', 'ikan', 'cumi', 'bebek', 'ceker', 'fillet'],
        'info_harga'         => ['harga', 'price', 'berapa', 'biaya', 'tarif', 'mahal', 'murah', 'diskon', 'promo', 'pricelist', 'daftar harga', 'potongan'],
        'daftar_b2b'         => ['b2b', 'mitra', 'partner', 'grosir', 'partai', 'daftar', 'registrasi', 'syarat', 'dokumen', 'perusahaan', 'kemitraan', 'mou', 'kontrak', 'kerja sama', 'pengadaan'],
        'info_pengiriman'    => ['kirim', 'pengiriman', 'delivery', 'ongkir', 'jarak', 'armada', 'mobil', 'truck', 'cold', 'antar', 'lalamove', 'kurir', 'ongkos kirim', 'transport'],
        'hubungi_admin'      => ['admin', 'cs', 'customer service', 'kontak', 'contact', 'whatsapp', 'wa', 'telepon', 'phone', 'email', 'hubungi', 'tanya', 'nomor hp', 'no hp', 'call center'],
        'sertifikasi'        => ['halal', 'nkv', 'bpom', 'sertifikat', 'bersih', 'higienis', 'higenis', 'veteriner', 'sehat', 'kualitas', 'segar', 'fresh', 'sertifikasi', 'mui'],
        'lokasi_operasional' => ['alamat', 'lokasi', 'dimana', 'gudang', 'kantor', 'pekanbaru', 'panam', 'jam buka', 'jam operasional', 'hari apa', 'tutup', 'operasional', 'alamatnya', 'posisi', 'map', 'maps', 'rute'],
        'minimal_order'      => ['minimal', 'minimum', 'paling sedikit', 'eceran', 'ecer', 'kg', 'ton', 'kilo', 'tonase', 'minimal order', 'min order', 'qty', 'kuantitas', 'pembelian'],
        'info_sla'           => ['sla', 'lama diproses', 'berapa lama', 'tenggat', 'cut off', 'cutoff', 'proses pengiriman', 'batal otomatis', 'refund otomatis', 'jam kerja', 'proses berapa lama', 'kapan dikirim', 'estimasi pengemasan'],
        'keluhan_pelanggan'  => ['keluhan', 'komplain', 'rusak', 'retur', 'garansi', 'dispute', 'refund', 'salah kirim', 'kurang', 'cacat', 'masalah', 'kecewa', 'dikembalikan'],
    ];

    // ─── Static flow definitions ───────────────────────────
    private array $flows = [
        'welcome' => [
            'message' => "Halo! 👋 Saya **APM Assistant**, siap membantu Anda.\n\nPilih topik di bawah atau ketik pertanyaan Anda:",
            'buttons' => [
                ['label' => '📦 Cek Stok Produk',    'flow' => 'stok_kategori'],
                ['label' => '💰 Info Harga & Katalog', 'flow' => 'info_harga'],
                ['label' => '🏢 Daftar Mitra B2B',    'flow' => 'daftar_b2b'],
                ['label' => '🚚 Info Pengiriman',      'flow' => 'info_pengiriman'],
                ['label' => '⏱️ SLA Proses & Cut-Off 17:00', 'flow' => 'info_sla'],
                ['label' => '⚠️ Keluhan & Komplain',   'flow' => 'keluhan_pelanggan'],
                ['label' => '🏅 Halal, NKV & Higienitas', 'flow' => 'sertifikasi'],
                ['label' => '📍 Alamat & Jam Kerja',   'flow' => 'lokasi_operasional'],
                ['label' => '⚖️ Ketentuan Min. Order', 'flow' => 'minimal_order'],
                ['label' => '📞 Hubungi Admin',        'flow' => 'hubungi_admin'],
            ],
        ],

        'info_sla' => [
            'message' => "⏱️ **SLA Layanan & Pengiriman APM**\n\n**1. Jam Operasional Gudang & Cut-Off:**\n• Jam Operasional: Senin – Jumat, 08.00 – 17.00 WIB\n• Order setelah pukul **17.00 WIB** / akhir pekan diproses hari kerja berikutnya.\n\n**2. Waktu Proses Pengemasan (Processing SLA):**\n• **B2C (Retail):** Maksimal **2×24 jam kerja (48 jam)** setelah pembayaran diverifikasi.\n• **B2B (Mitra):** Review & Ongkir 1×24 jam kerja; Penyiapan stok & armada 2–3 hari kerja.\n\n**3. Garansi SLA & Auto-Cancel:**\n• Pesanan B2C lunas yang belum diproses melebihi SLA (2×24 jam kerja) akan **otomatis dibatalkan oleh sistem**, stok dipulihkan, dan status refund diproses.\n\n**4. Bantuan Keterlambatan Verifikasi / Pemrosesan:**\n• Jika pesanan Anda membutuhkan konfirmasi atau penanganan mendesak, Anda disarankan untuk **langsung menghubungi Admin CS via WhatsApp (+62 812-3456-7890)** atau Formulir Kontak di website dengan menyertakan Nomor Pesanan.",
            'buttons' => [
                ['label' => '📞 Chat Admin CS (WhatsApp)', 'flow' => 'hubungi_admin'],
                ['label' => '🚚 Info Armada & Ongkir',     'flow' => 'info_pengiriman'],
                ['label' => '🏠 Menu Utama',                'flow' => 'welcome'],
            ],
        ],

        'keluhan_pelanggan' => [
            'message' => "⚠️ **Panduan Keluhan & Komplain Pelanggan**\n\nJika Anda mengalami kendala pada pesanan (barang rusak, tidak sesuai, atau kendala pengiriman):\n\n**1. Pelanggan Retail (B2C):**\n• Kirimkan Nomor Pesanan, foto/video unboxing, dan deskripsi keluhan ke Admin CS via WhatsApp.\n• Keluhan diproses maksimal 1x24 jam kerja untuk garansi pengembalian dana / penggantian produk.\n\n**2. Mitra Bisnis (B2B):**\n• Pengajuan keluhan dilakukan langsung via **Portal B2B (Dispute System)** di halaman detail pesanan.\n• Disertai bukti foto fisik barang saat armada tiba.\n\n**3. Kebijakan Refund / Garansi:**\n• Semua barang yang terbukti rusak/cacat saat diterima akan diproses klaimnya secara transparan.",
            'buttons' => [
                ['label' => '📋 Pesanan Saya',          'link' => '/orders'],
                ['label' => '📞 Chat Admin CS (WhatsApp)', 'flow' => 'hubungi_admin'],
                ['label' => '🏠 Menu Utama',            'flow' => 'welcome'],
            ],
        ],

        'info_harga' => [
            'message' => "💰 **Info Harga Produk**\n\nHarga masing-masing produk sudah tercantum di halaman katalog kami dan diperbarui secara berkala.\n\n📌 Harga B2C (retail) tersedia di halaman produk.\n📌 Harga B2B (grosir) didapatkan setelah mendaftar sebagai Mitra B2B.",
            'buttons' => [
                ['label' => '📋 Lihat Katalog Produk', 'link' => '/products'],
                ['label' => '🏢 Info Mitra B2B',        'flow' => 'daftar_b2b'],
                ['label' => '🏠 Menu Utama',             'flow' => 'welcome'],
            ],
        ],

        'daftar_b2b' => [
            'message' => "🏢 **Pendaftaran Mitra B2B**\n\nDapatkan harga grosir eksklusif dan layanan prioritas!\n\n**Cara daftar:**\n1. Klik tombol Register di navbar\n2. Pilih *Daftar sebagai B2B*\n3. Isi data perusahaan & upload dokumen\n4. Tunggu persetujuan admin (1–3 hari kerja)\n5. Akses portal B2B Anda!\n\n**Dokumen yang diperlukan:**\n• NIB / SIUP\n• NPWP Perusahaan\n• KTP Penanggung Jawab",
            'buttons' => [
                ['label' => '📝 Daftar Sekarang', 'link' => '/register'],
                ['label' => '📞 Tanya Admin',      'flow' => 'hubungi_admin'],
                ['label' => '🏠 Menu Utama',        'flow' => 'welcome'],
            ],
        ],

        'info_pengiriman' => [
            'message' => "🚚 **Info Pengiriman APM**\n\nKami menjamin produk tiba dalam kondisi segar!\n\n**Armada kami:**\n• 🚛 Truk berpendingin (*cold chain*) untuk daging & seafood\n• 🚐 Kendaraan ringan untuk pengiriman dalam kota\n\n**Coverage area:**\n• Pengiriman dalam kota: setiap hari\n• Luar kota / antar provinsi: jadwal tertentu\n\n**Jam Operasional:** Senin–Sabtu, 07.00–17.00 WIB\n\nUntuk pengecekan ongkir, hubungi admin kami.",
            'buttons' => [
                ['label' => '📞 Tanya Ongkir ke Admin', 'flow' => 'hubungi_admin'],
                ['label' => '🏠 Menu Utama',              'flow' => 'welcome'],
            ],
        ],

        'hubungi_admin' => [
            'message' => "📞 **Hubungi Kami**\n\nTim kami siap membantu Anda!\n\n📱 **WhatsApp:** +62 812-3456-7890\n📧 **Email:** info@agropanganmaju.com\n🕐 **Jam Layanan:** Senin–Sabtu, 08.00–17.00 WIB\n\nKlik tombol di bawah untuk langsung chat via WhatsApp:",
            'buttons' => [
                ['label' => '💬 Chat WhatsApp Sekarang', 'link' => 'https://wa.me/6281234567890?text=Halo%20Admin%20APM%2C%20saya%20ingin%20bertanya...', 'external' => true],
                ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
            ],
        ],

        'stok_kategori' => [
            'message' => "📦 **Cek Stok Produk**\n\nPilih kategori produk yang ingin Anda cek:",
            'dynamic' => 'categories', // will be resolved dynamically
        ],

        'sertifikasi' => [
            'message' => "🏅 **Sertifikasi & Kualitas Produk APM**\n\nSeluruh produk Meat, Poultry, dan Seafood PT. Agro Pangan Maju dijamin kesegaran dan kebersihannya:\n\n• ✅ **Sertifikasi Halal MUI**: Menjamin seluruh produk daging dan unggas halal dikonsumsi.\n• ✅ **Nomor Kontrol Veteriner (NKV)**: Bukti terpenuhinya syarat kelayakan higienis-sanitasi sebagai jaminan keamanan pangan asal hewan.\n• 🧊 **Cold Chain System**: Produk disimpan di cold storage suhu -18°C hingga -20°C.",
            'buttons' => [
                ['label' => '📞 Hubungi Admin', 'flow' => 'hubungi_admin'],
                ['label' => '🏠 Menu Utama',     'flow' => 'welcome'],
            ],
        ],

        'lokasi_operasional' => [
            'message' => "📍 **Lokasi & Jam Operasional APM**\n\n🏢 **Alamat Gudang Utama:**\nPergudangan Panam Bizpark No. B-19, Jl. HR. Subrantas, Pekanbaru, Riau.\n\n🕐 **Jam Operasional:**\nSenin – Sabtu: 08.00 – 17.00 WIB\n*(Minggu dan Hari Libur Nasional tutup)*\n\n📌 *Disarankan menghubungi admin via WhatsApp terlebih dahulu sebelum melakukan pengambilan langsung ke gudang.*",
            'buttons' => [
                ['label' => '📞 Chat WhatsApp', 'flow' => 'hubungi_admin'],
                ['label' => '🏠 Menu Utama',     'flow' => 'welcome'],
            ],
        ],

        'minimal_order' => [
            'message' => "⚖️ **Ketentuan Batas Pembelian (Minimum Order)**\n\n• 🛒 **Pelanggan Retail (B2C):** Tidak ada minimal order untuk pembelian eceran, namun pembelian produk tertentu diatur minimal 10 KG dan maksimal 50 KG per checkout.\n• 🏢 **Mitra Bisnis (B2B):** Minimum order adalah **1 Ton (1.000 KG)** dan maksimal **10 Ton (10.000 KG)** per transaksi pengadaan.",
            'buttons' => [
                ['label' => '📋 Katalog Produk B2C', 'link' => '/products'],
                ['label' => '🏢 Pendaftaran B2B',     'flow' => 'daftar_b2b'],
                ['label' => '🏠 Menu Utama',          'flow' => 'welcome'],
            ],
        ],
    ];

    // ─── Main Handler ──────────────────────────────────────
    public function reply(Request $request)
    {
        $request->validate(['message' => 'nullable|string|max:500', 'flow' => 'nullable|string']);

        $flow    = trim($request->input('flow', ''));
        $message = trim(strtolower($request->input('message', '')));

        // 1. Flow request (button clicked)
        if ($flow) {
            return $this->resolveFlow($flow);
        }

        // 2. Free text: find matching flow via keyword with word-boundary check
        if ($message !== '') {
            // Clean punctuation and wrap in spaces for exact word boundary match
            $cleanMessage = ' ' . str_replace(['?', '.', ',', '!', '-', '_'], ' ', $message) . ' ';
            $cleanMessage = preg_replace('/\s+/', ' ', $cleanMessage);

            foreach ($this->keywordMap as $flowId => $keywords) {
                foreach ($keywords as $kw) {
                    if (str_contains($cleanMessage, ' ' . $kw . ' ')) {
                        return $this->resolveFlow($flowId);
                    }
                }
            }
            // Fallback
            return response()->json([
                'message' => "🤔 Maaf, saya belum bisa menjawab pertanyaan itu.\n\nCoba pilih topik di bawah, atau hubungi admin kami secara langsung.",
                'buttons' => [
                    ['label' => '📞 Hubungi Admin', 'flow' => 'hubungi_admin'],
                    ['label' => '🏠 Menu Utama',     'flow' => 'welcome'],
                ],
            ]);
        }

        // 3. Default: welcome
        return $this->resolveFlow('welcome');
    }

    // ─── Resolve a named flow ──────────────────────────────
    private function resolveFlow(string $flowId)
    {
        // Dynamic: list categories
        if ($flowId === 'stok_kategori') {
            return $this->getCategoryButtons();
        }

        // Dynamic: products by category
        if (str_starts_with($flowId, 'stok_cat_')) {
            $catId = (int) str_replace('stok_cat_', '', $flowId);
            return $this->getProductsByCategory($catId);
        }

        // Static flow
        if (isset($this->flows[$flowId])) {
            return response()->json($this->flows[$flowId]);
        }

        // Unknown flow → welcome
        return response()->json($this->flows['welcome']);
    }

    // ─── Dynamic: categories list ──────────────────────────
    private function getCategoryButtons()
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true)->where('stock_b2c', '>', 0);
        }])->get();

        $buttons = [];
        foreach ($categories as $cat) {
            $buttons[] = [
                'label' => '🗂 ' . $cat->name,
                'flow'  => 'stok_cat_' . $cat->id,
            ];
        }
        $buttons[] = ['label' => '📋 Semua Produk', 'link' => '/products'];
        $buttons[] = ['label' => '🏠 Menu Utama',    'flow' => 'welcome'];

        return response()->json([
            'message' => "📦 **Cek Stok Produk**\n\nPilih kategori yang ingin Anda cek:",
            'buttons' => $buttons,
        ]);
    }

    // ─── Dynamic: products by category with live stock ────
    private function getProductsByCategory(int $catId)
    {
        $category = Category::find($catId);
        if (!$category) {
            return $this->resolveFlow('stok_kategori');
        }

        $products = Product::where('category_id', $catId)
                           ->where('is_active', true)
                           ->orderBy('name')
                           ->get(['id', 'name', 'slug', 'stock_b2c', 'price', 'unit']);

        if ($products->isEmpty()) {
            return response()->json([
                'message' => "😔 Saat ini belum ada produk aktif di kategori **{$category->name}**.\n\nSilakan cek kategori lain atau hubungi admin.",
                'buttons' => [
                    ['label' => '🔙 Pilih Kategori Lain', 'flow' => 'stok_kategori'],
                    ['label' => '📞 Hubungi Admin',        'flow' => 'hubungi_admin'],
                    ['label' => '🏠 Menu Utama',            'flow' => 'welcome'],
                ],
            ]);
        }

        // Build product list message
        $lines = ["📦 **Stok {$category->name}**\n"];
        foreach ($products as $p) {
            $stockLabel = $p->stock_b2c > 0
                ? "✅ *{$p->stock_b2c} KG* tersedia"
                : "❌ Stok habis";
            $harga = 'Rp ' . number_format($p->price, 0, ',', '.');
            $lines[] = "• **{$p->name}** — {$harga}/{$p->unit}\n  {$stockLabel}";
        }
        $message = implode("\n", $lines);

        // Add product detail links
        $buttons = [];
        foreach ($products as $p) {
            $buttons[] = [
                'label' => '🔍 Detail: ' . $p->name,
                'link'  => '/product/' . $p->slug,
            ];
        }
        $buttons[] = ['label' => '🔙 Kembali ke Kategori', 'flow' => 'stok_kategori'];
        $buttons[] = ['label' => '🏠 Menu Utama',            'flow' => 'welcome'];

        return response()->json([
            'message' => $message,
            'buttons' => $buttons,
        ]);
    }
}
