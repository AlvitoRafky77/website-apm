<?php

namespace App\Http\Controllers;

use App\Models\B2bOrder;
use App\Models\B2bRegistration;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2bChatbotController extends Controller
{
    private array $keywordMap = [
        'welcome' => [
            'halo', 'hai', 'hello', 'hi', 'selamat', 'pagi', 'siang', 'sore', 'malam',
            'menu', 'bantuan', 'help', 'mulai', 'start', 'helo', 'hallo', 'asisten', 'assistant', 'bot',
        ],
        'stok_b2b' => [
            'stok', 'stock', 'ketersediaan', 'produk b2b', 'grosir', 'partai besar', 'katalog',
            'harga grosir', 'cek produk', 'cek stok', 'barang tersedia', 'daging', 'ayam', 'seafood',
            'sapi', 'udang', 'ikan', 'cumi', 'bebek', 'fillet',
        ],
        'order_status' => [
            'order', 'pesanan', 'status order', 'riwayat', 'transaksi', 'tracking', 'pelacakan',
            'orderan', 'membeli', 'pemesanan',
        ],
        'payment_info' => [
            'dp', 'down payment', 'pelunasan', 'pembayaran', 'bayar', 'transfer', 'invoice',
            'rekening', 'bukti bayar', 'tempo', 'termin', 'tf', 'bank bca', 'dki', 'rekening bca',
        ],
        'shipping_info' => [
            'pengiriman', 'delivery', 'kirim', 'jadwal kirim', 'driver', 'spb', 'surat jalan',
            'logistik', 'ongkir', 'armada', 'truk', 'mobil box', 'freezer', 'suhu freezer',
        ],
        'complaint_info' => [
            'komplain', 'dispute', 'keluhan', 'retur', 'klaim', 'masalah barang', 'barang rusak',
            'kurang timbangan', 'asuransi', 'potongan klaim', 'klaim kerusakan',
        ],
        'account_info' => [
            'akun', 'profil', 'perusahaan', 'mou', 'status mitra', 'registrasi', 'approval',
            'pendaftaran', 'dokumen', 'nib', 'siup', 'npwp', 'perjanjian', 'kontrak',
        ],
        'contact_am' => [
            'admin', 'am', 'account manager', 'cs', 'customer service', 'kontak', 'whatsapp',
            'wa', 'hubungi', 'telepon', 'email', 'am prioritias',
        ],
        'sertifikasi' => [
            'halal', 'nkv', 'bpom', 'sertifikat', 'bersih', 'higienis', 'higenis', 'veteriner',
            'sehat', 'kualitas', 'segar', 'fresh', 'sertifikasi', 'mui',
        ],
        'lokasi_operasional' => [
            'alamat', 'lokasi', 'dimana', 'gudang', 'kantor', 'pekanbaru', 'panam', 'jam buka',
            'jam operasional', 'hari apa', 'tutup', 'operasional', 'alamatnya', 'posisi', 'map', 'maps',
        ],
        'info_sla' => [
            'sla', 'lama diproses', 'berapa lama', 'tenggat', 'cut off', 'cutoff', 'proses pengiriman',
            'jam kerja', 'proses berapa lama', 'kapan dikirim', 'estimasi pengemasan', 'sla b2b',
        ],
    ];

    private array $flows = [
        'welcome' => [
            'message' => "Halo, Mitra B2B APM 👋\n\nSaya bisa membantu Anda mengecek status order, info pembayaran, pengiriman, komplain, dan profil kemitraan.",
            'buttons' => [
                ['label' => '📦 Status Order',        'flow' => 'order_status'],
                ['label' => '💳 Info Pembayaran',     'flow' => 'payment_info'],
                ['label' => '🚚 Info Pengiriman',     'flow' => 'shipping_info'],
                ['label' => '⏱️ SLA Pemrosesan (2-3 Hari)', 'flow' => 'info_sla'],
                ['label' => '⚠️ Komplain & Dispute',  'flow' => 'complaint_info'],
                ['label' => '🏢 Profil Mitra & MoU',   'flow' => 'account_info'],
                ['label' => '🏅 Halal, NKV & Kualitas', 'flow' => 'sertifikasi'],
                ['label' => '📍 Alamat & Jam Kerja',   'flow' => 'lokasi_operasional'],
                ['label' => '📞 Hubungi AM Prioritas',  'flow' => 'contact_am'],
            ],
        ],

        'info_sla' => [
            'message' => "⏱️ **SLA Pemrosesan & Pengiriman B2B**\n\n**1. Jam Operasional & Cut-Off:**\n• Jam Layanan: Senin – Jumat, 08.00 – 17.00 WIB\n• Cut-off order & verifikasi: **17.00 WIB**\n\n**2. Tahap Pemrosesan & SLA B2B:**\n• **Review Order & Hitung Ongkir Kota:** Maksimal **1×24 jam kerja** oleh Admin.\n• **Penyiapan & Cold Storage QC:** **2–3 hari kerja** terhitung setelah DP 50% diverifikasi Admin APM.\n\n**3. Kendala Verifikasi & Bantuan Khusus:**\n• Jika pesanan B2B Anda belum di-review, belum diinputkan ongkir, atau DP belum terverifikasi melebihi 1–2 hari kerja, Anda disarankan untuk **langsung menghubungi Account Manager (AM) Prioritas** via WhatsApp CS (+62 812-3456-7890) atau Formulir Kontak di website dengan menyertakan Nomor Order B2B Anda.",
            'buttons' => [
                ['label' => '📞 Hubungi AM Prioritas (WA)', 'flow' => 'contact_am'],
                ['label' => '🚚 Status Pengiriman',        'flow' => 'shipping_info'],
                ['label' => '🏠 Menu Utama',                 'flow' => 'welcome'],
            ],
        ],

        'sertifikasi' => [
            'message' => "🏅 **Sertifikasi & Kualitas Produk APM**\n\nSeluruh produk Meat, Poultry, dan Seafood PT. Agro Pangan Maju dijamin kesegaran dan kebersihannya:\n\n• ✅ **Sertifikasi Halal MUI**: Menjamin seluruh produk daging dan unggas halal dikonsumsi.\n• ✅ **Nomor Kontrol Veteriner (NKV)**: Bukti terpenuhinya syarat kelayakan higienis-sanitasi sebagai jaminan keamanan pangan asal hewan.\n• 🧊 **Cold Chain System**: Produk disimpan di cold storage suhu -18°C hingga -20°C.",
            'buttons' => [
                ['label' => '📞 Hubungi AM', 'flow' => 'contact_am'],
                ['label' => '🏠 Menu Utama',  'flow' => 'welcome'],
            ],
        ],

        'lokasi_operasional' => [
            'message' => "📍 **Lokasi & Jam Operasional APM**\n\n🏢 **Alamat Gudang Utama:**\nPergudangan Panam Bizpark No. B-19, Jl. HR. Subrantas, Pekanbaru, Riau.\n\nSimulasi peta rute lokasi pergudangan dapat diakses di menu utama.\n\n🕐 **Jam Operasional:**\nSenin – Sabtu: 08.00 – 17.00 WIB\n*(Minggu dan Hari Libur Nasional tutup)*",
            'buttons' => [
                ['label' => '📞 Hubungi AM', 'flow' => 'contact_am'],
                ['label' => '🏠 Menu Utama',  'flow' => 'welcome'],
            ],
        ],
    ];

    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
            'flow' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        if (!$user || !method_exists($user, 'isB2b') || !$user->isB2b()) {
            return response()->json([
                'message' => 'Akses chatbot B2B hanya tersedia untuk mitra yang sudah login.',
                'buttons' => [
                    ['label' => '🏠 Ke Beranda', 'link' => route('home')],
                ],
            ], 403);
        }

        $flow = trim((string) $request->input('flow', ''));
        $message = trim(strtolower((string) $request->input('message', '')));

        if ($flow !== '') {
            return $this->resolveFlow($flow, $user->id);
        }

        if ($message !== '') {
            // Clean punctuation and wrap in spaces for exact word boundary match
            $cleanMessage = ' ' . str_replace(['?', '.', ',', '!', '-', '_'], ' ', $message) . ' ';
            $cleanMessage = preg_replace('/\s+/', ' ', $cleanMessage);

            foreach ($this->keywordMap as $flowId => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($cleanMessage, ' ' . $keyword . ' ')) {
                        return $this->resolveFlow($flowId, $user->id);
                    }
                }
            }

            return response()->json([
                'message' => "🤔 Maaf, saya belum menemukan jawaban yang sesuai.\n\nCoba tanyakan status order, pembayaran, pengiriman, komplain, atau profil mitra Anda.",
                'buttons' => [
                    ['label' => '📦 Status Order', 'flow' => 'order_status'],
                    ['label' => '💳 Info Pembayaran', 'flow' => 'payment_info'],
                    ['label' => '💬 Hubungi AM', 'flow' => 'contact_am'],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        return $this->resolveFlow('welcome', $user->id);
    }

    private function resolveFlow(string $flowId, int $userId)
    {
        if ($flowId === 'stok_b2b') {
            return response()->json($this->buildStockMessage());
        }

        if ($flowId === 'order_status') {
            return response()->json([
                'message' => $this->buildOrderStatusMessage(),
                'buttons' => [
                    ['label' => '📋 Riwayat Pesanan', 'link' => route('b2b.orders.index')],
                    ['label' => '➕ Buat Order Baru', 'link' => route('b2b.orders.create')],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if ($flowId === 'payment_info') {
            return response()->json([
                'message' => '<strong>💳 Info Pembayaran B2B</strong><br><br><ol class="list-decimal pl-5 space-y-1">'
                    . '<li>DP 50% dibayarkan setelah order dibuat.</li>'
                    . '<li>Pelunasan dilakukan saat barang sudah dalam pengiriman.</li>'
                    . '<li>Bukti pembayaran diunggah pada halaman detail pesanan.</li>'
                    . '<li>Status order berubah setelah verifikasi admin selesai.</li>'
                    . '</ol><br><span class="text-xs text-gray-500">Jika butuh bantuan verifikasi, silakan hubungi Account Manager.</span>',
                'buttons' => [
                    ['label' => '📋 Lihat Pesanan', 'link' => route('b2b.orders.index')],
                    ['label' => '💬 Hubungi AM', 'flow' => 'contact_am'],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if ($flowId === 'shipping_info') {
            return response()->json([
                'message' => '<strong>🚚 Info Pengiriman B2B</strong><br><br><ul class="list-disc pl-5 space-y-1">'
                    . '<li>Pengiriman diproses setelah DP terverifikasi.</li>'
                    . '<li>Jadwal kirim akan dicantumkan pada detail order.</li>'
                    . '<li>Data driver, nomor kendaraan, dan SPB akan muncul pada pesanan terkait.</li>'
                    . '<li>Status pengiriman dapat dipantau dari halaman order.</li>'
                    . '</ul><br><span class="text-xs text-gray-500">Pastikan alamat penerima dan nomor kontak sudah benar sebelum pengiriman dijadwalkan.</span>',
                'buttons' => [
                    ['label' => '📋 Riwayat Pesanan', 'link' => route('b2b.orders.index')],
                    ['label' => '⚠️ Ajukan Komplain', 'flow' => 'complaint_info'],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if ($flowId === 'complaint_info') {
            return response()->json([
                'message' => '<strong>⚠️ Komplain B2B</strong><br><br><ol class="list-decimal pl-5 space-y-1">'
                    . '<li>Komplain dapat diajukan melalui halaman detail pesanan.</li>'
                    . '<li>Bisa diajukan saat status order sudah selesai atau terjadi kendala pengiriman.</li>'
                    . '<li>Lampirkan deskripsi masalah dan bukti foto.</li>'
                    . '<li>Data lengkap akan membantu admin meninjau lebih cepat.</li>'
                    . '</ol>',
                'buttons' => [
                    ['label' => '📋 Buka Pesanan', 'link' => route('b2b.orders.index')],
                    ['label' => '💬 Hubungi AM', 'flow' => 'contact_am'],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if ($flowId === 'account_info') {
            return response()->json([
                'message' => $this->buildAccountMessage(),
                'buttons' => [
                    ['label' => '🛒 Dashboard B2B', 'link' => route('b2b.dashboard')],
                    ['label' => '📋 Riwayat Pesanan', 'link' => route('b2b.orders.index')],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if ($flowId === 'contact_am') {
            return response()->json([
                'message' => '<strong>📞 Hubungi Account Manager</strong><br><br><ul class="list-disc pl-5 space-y-1">'
                    . '<li>WhatsApp: +62 812-3456-7890</li>'
                    . '<li>Email: info@agropanganmaju.com</li>'
                    . '<li>Jam layanan: Senin–Sabtu, 08.00–17.00 WIB</li>'
                    . '</ul><br><span class="text-xs text-gray-500">Silakan gunakan tombol WhatsApp untuk komunikasi langsung.</span>',
                'buttons' => [
                    ['label' => '💬 Chat WhatsApp', 'link' => 'https://wa.me/6281234567890?text=Halo%20Admin%20APM%2C%20saya%20mitra%20B2B%20dan%20perlu%20bantuan.', 'external' => true],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ]);
        }

        if (!isset($this->flows[$flowId])) {
            return response()->json($this->flows['welcome']);
        }

        return response()->json($this->flows[$flowId]);
    }

    private function buildOrderStatusMessage(): string
    {
        $user = Auth::user();
        if (!$user) {
            return '<strong>📦 Status Order</strong><br><br>Silakan login untuk melihat status pesanan Anda.';
        }

        $orders = B2bOrder::where('user_id', $user->id)->latest()->take(5)->get();

        if ($orders->isEmpty()) {
            return '<strong>📦 Status Order</strong><br><br>Anda belum memiliki pesanan B2B. Silakan buat order baru melalui dashboard B2B.';
        }

        $lines = ['<strong>📦 Status Order Terbaru</strong><br><br><ul class="list-disc pl-5 space-y-2">'];
        foreach ($orders as $order) {
            $statusLine = '<li><strong>' . e($order->order_number) . '</strong> — ' . e($order->status_label);
            if ($order->dispute_status && $order->dispute_status !== 'none') {
                $statusLine .= '<br><span class="text-gray-500">Komplain: ' . e($order->dispute_status_label) . '</span>';
            }
            $statusLine .= '</li>';
            $lines[] = $statusLine;
        }

        $lines[] = '</ul><div class="text-xs text-gray-500 mt-2">Gunakan halaman detail order untuk melihat DP, pelunasan, pengiriman, dan komplain.</div>';
        return implode('', $lines);
    }

    private function buildStockMessage(): array
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('stock_b2b', '>', 0)
            ->with('category')
            ->orderByDesc('stock_b2b')
            ->orderBy('name')
            ->take(6)
            ->get();

        if ($products->isEmpty()) {
            return [
                'message' => '<strong>📦 Stok Produk B2B</strong><br><br>Saat ini belum ada produk B2B yang tersedia. Silakan cek kembali nanti atau hubungi Account Manager.',
                'buttons' => [
                    ['label' => '💬 Hubungi AM', 'flow' => 'contact_am'],
                    ['label' => '🏠 Menu Utama', 'flow' => 'welcome'],
                ],
            ];
        }

        $items = [];
        $buttons = [];
        foreach ($products as $product) {
            $productUrl = route('b2b.products.show', $product->slug);
            $stockTon = number_format(((float) $product->stock_b2b) / 1000, 1, ',', '.');
            $price = number_format((float) $product->price, 0, ',', '.');
            $category = e($product->category->name ?? '-');
            $name = e($product->name);

            $items[] = '<li class="space-y-1">'
                . '<div><strong>' . $name . '</strong> <span class="text-gray-500">(' . $category . ')</span></div>'
                . '<div class="text-gray-600">Stok: ' . $stockTon . ' Ton · Rp ' . $price . '/KG</div>'
                . '<a href="' . e($productUrl) . '" target="_blank" rel="noopener noreferrer" class="text-[#CD422B] font-semibold underline">Lihat detail produk</a>'
                . '</li>';

            $buttons[] = [
                'label' => '🔍 ' . $product->name,
                'link' => $productUrl,
            ];
        }

        $buttons[] = ['label' => '🛒 Buka Dashboard B2B', 'link' => route('b2b.dashboard')];
        $buttons[] = ['label' => '🏠 Menu Utama', 'flow' => 'welcome'];

        return [
            'message' => '<strong>📦 Stok Produk B2B</strong><br><br>Berikut produk yang masih tersedia:<br><br><ul class="list-disc pl-5 space-y-2">'
                . implode('', $items)
                . '</ul><div class="text-xs text-gray-500 mt-2">Klik produk untuk membuka detail dan cek stok lebih lengkap.</div>',
            'buttons' => $buttons,
        ];
    }

    private function buildAccountMessage(): string
    {
        $user = Auth::user();
        $registration = $user?->b2bRegistration ?? B2bRegistration::where('user_id', $user?->id)->first();

        if (!$registration) {
            return '<strong>🏢 Profil Mitra</strong><br><br>Data kemitraan belum ditemukan. Silakan lengkapi pendaftaran B2B Anda.';
        }

        $approvedAt = $registration->approved_at
            ? $registration->approved_at->format('d M Y H:i')
            : '-';

        return '<strong>🏢 Profil Mitra Anda</strong><br><br><ul class="list-disc pl-5 space-y-1">'
            . '<li>Perusahaan: <strong>' . e($registration->company_name) . '</strong></li>'
            . '<li>PIC: ' . e($registration->pic_name) . '</li>'
            . '<li>Status: ' . e($registration->statusLabel) . '</li>'
            . '<li>Kelengkapan dokumen: <strong>' . $registration->completeness . '%</strong></li>'
            . '<li>Disetujui pada: ' . e($approvedAt) . '</li>'
            . '</ul><br><span class="text-xs text-gray-500">Jika ada data yang perlu diperbarui, silakan hubungi admin B2B.</span>';
    }
}
