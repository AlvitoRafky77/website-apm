<?php

namespace App\Console\Commands;

use App\Models\B2bOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSlaCancellation extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'orders:check-sla {--hours=0 : Simulasi majukan waktu sebanyak X jam untuk pengujian langsung}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Pemeriksaan SLA berkala: Monitoring pesanan perlu diproses, tenggat SLA, & auto-cancel B2C (2x24 jam kerja).';

    /**
     * Eksekusi perintah konsol.
     */
    public function handle(): int
    {
        $simulatedHours = (int) $this->option('hours');
        $now = now()->addHours($simulatedHours);

        $this->info("================================================================================");
        $this->info("             🤖 APM SLA MONITORING & CANCELLATION ENGINE                        ");
        $this->info("================================================================================");
        $this->line("Waktu Pengecekan Sistem : " . $now->format('d F Y, H:i:s') . " WIB");
        if ($simulatedHours > 0) {
            $this->warn("⚠️ MODE SIMULASI AKTIF: Majukan waktu +{$simulatedHours} jam untuk pengujian!");
        }
        $this->newLine();

        // ─── 1. B2C ORDERS MONITORING & AUTO-CANCEL ───
        $this->info("📌 [B2C RETAIL] DAFTAR PESANAN PERLU DIPROSES (SLA 2x24 JAM KERJA)");
        $this->line("--------------------------------------------------------------------------------");

        $activeB2cOrders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($activeB2cOrders->isEmpty()) {
            $this->comment("  Tidak ada pesanan B2C lunas yang sedang menunggu pemrosesan.");
        } else {
            $rows = [];
            $overdueOrders = [];

            foreach ($activeB2cOrders as $order) {
                $paidTime = $order->paid_at ?? $order->created_at;
                $deadline = $paidTime->copy()->addHours(48);
                $diffHours = (int) $now->diffInHours($deadline, false);

                if ($diffHours < 0) {
                    $slaStatus = '❌ TERLEWAT (AUTO-CANCEL)';
                    $overdueOrders[] = $order;
                } elseif ($diffHours <= 12) {
                    $slaStatus = '⚠️ SEGERA HABIS (' . ceil($diffHours) . ' Jam)';
                } else {
                    $slaStatus = '✅ SLA AMAN (Sisa ' . ceil($diffHours) . ' Jam)';
                }

                $rows[] = [
                    $order->order_id,
                    $order->recipient_name,
                    $paidTime->format('d/m/Y H:i'),
                    $deadline->format('d/m/Y H:i'),
                    $diffHours < 0 ? abs($diffHours) . ' Jam Lewat' : ceil($diffHours) . ' Jam',
                    $slaStatus,
                ];
            }

            $this->table(
                ['Order ID', 'Pelanggan', 'Waktu Bayar', 'Tenggat SLA (48 Jam)', 'Sisa Waktu', 'Status SLA'],
                $rows
            );

            // Process Overdue Orders
            if (!empty($overdueOrders)) {
                $this->newLine();
                $this->warn("⚙️ [AUTO-CANCEL ENGINE] Memproses " . count($overdueOrders) . " pesanan B2C yang melewati SLA 48 jam...");

                foreach ($overdueOrders as $order) {
                    $this->warn("  [-] Membatalkan Order: {$order->order_id} ({$order->recipient_name})");

                    if (!empty($order->items) && is_array($order->items)) {
                        foreach ($order->items as $item) {
                            $productId = $item['id'] ?? $item['product_id'] ?? null;
                            $qty       = (int) ($item['quantity'] ?? $item['qty'] ?? 1);

                            if ($productId) {
                                $product = Product::find($productId);
                                if ($product) {
                                    $product->increment('stock_b2c', $qty);
                                    $this->line("      + Restock {$product->name}: +{$qty} KG (Stok: {$product->stock_b2c} KG)");
                                }
                            }
                        }
                    }

                    $order->update([
                        'status'         => 'cancelled',
                        'refund_amount'  => $order->total,
                        'refunded_at'    => $now,
                        'refund_reason'  => 'Dibatalkan Otomatis Sistem (SLA Processing Timeout 2x24 Jam Kerja)',
                    ]);

                    Log::info("[SLA Auto-Cancel] Order {$order->order_id} dibatalkan otomatis karena melebihi SLA 2x24 jam kerja. Stok dipulihkan.");
                }
                $this->info("✅ [B2C SLA Engine] Berhasil membatalkan pesanan lewat SLA & mengembalikan stok.");
            }
        }

        // ─── 2. B2B ORDERS MONITORING ───
        $this->newLine();
        $this->info("📌 [B2B MITRA] DAFTAR PESANAN PERLU DIPROSES (SLA 2-3 HARI KERJA)");
        $this->line("--------------------------------------------------------------------------------");

        $activeB2bOrders = B2bOrder::whereIn('status', ['dp_verified', 'in_transit'])
            ->whereNotNull('dp_verified_at')
            ->get();

        if ($activeB2bOrders->isEmpty()) {
            $this->comment("  Tidak ada pesanan B2B terverifikasi yang sedang dalam fase pengemasan/armada.");
        } else {
            $b2bRows = [];
            foreach ($activeB2bOrders as $b2b) {
                $verifiedTime = $b2b->dp_verified_at;
                $deadline = $verifiedTime->copy()->addHours(72); // 3 Hari Kerja
                $diffHours = (int) $now->diffInHours($deadline, false);

                if ($diffHours < 0) {
                    $slaStatus = '❌ TERLEWAT (>3 Hari)';
                } elseif ($diffHours <= 24) {
                    $slaStatus = '⚠️ HARI TERAKHIR (Sisa ' . ceil($diffHours) . ' Jam)';
                } else {
                    $slaStatus = '✅ SLA AMAN (Sisa ' . ceil($diffHours) . ' Jam)';
                }

                $company = $b2b->b2bRegistration->company_name ?? 'Mitra B2B';

                $b2bRows[] = [
                    $b2b->order_number,
                    $company,
                    $verifiedTime->format('d/m/Y H:i'),
                    $deadline->format('d/m/Y H:i'),
                    $diffHours < 0 ? abs($diffHours) . ' Jam Lewat' : ceil($diffHours) . ' Jam',
                    $slaStatus,
                ];
            }

            $this->table(
                ['Nomor Order', 'Mitra Perusahaan', 'DP Verified', 'Tenggat SLA (72 Jam)', 'Sisa Waktu', 'Status SLA'],
                $b2bRows
            );
        }

        $this->newLine();
        $this->info("================================================================================");
        $this->info("✅ Pemeriksaan SLA Selesai. Seluruh data transaksi & status SLA terpantau aman.");
        $this->info("================================================================================");

        return Command::SUCCESS;
    }
}
