<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('b2b_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // B2B-YYYYMMDD-XXXX

            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('b2b_registration_id')->constrained('b2b_registrations')->cascadeOnDelete();

            // Produk & harga
            $table->json('items');                         // [{product_id, name, qty_ton, price_per_kg, subtotal}]
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_cost', 15, 2)->default(0); // diisi admin
            $table->decimal('total', 15, 2);

            // Pembayaran DP (50%)
            $table->decimal('dp_amount', 15, 2)->nullable();
            $table->string('dp_proof_path')->nullable();
            $table->timestamp('dp_uploaded_at')->nullable();
            $table->timestamp('dp_verified_at')->nullable();
            $table->foreignId('dp_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('dp_admin_notes')->nullable();

            // Pembayaran Pelunasan (50%)
            $table->decimal('settlement_amount', 15, 2)->nullable();
            $table->string('settlement_proof_path')->nullable();
            $table->timestamp('settlement_uploaded_at')->nullable();
            $table->timestamp('settlement_verified_at')->nullable();
            $table->foreignId('settlement_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('settlement_admin_notes')->nullable();

            // Pengiriman
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->string('recipient_city');
            $table->date('delivery_date')->nullable();         // dijadwalkan admin
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('waybill_number')->nullable();
            $table->text('delivery_notes')->nullable();

            // Surat Penerimaan Barang (SPB)
            $table->string('spb_path')->nullable();
            $table->timestamp('spb_uploaded_at')->nullable();
            $table->timestamp('received_at')->nullable();

            // Dispute / Komplain
            $table->enum('dispute_status', ['none', 'open', 'reviewing', 'resolved', 'rejected'])->default('none');
            $table->text('dispute_reason')->nullable();
            $table->json('dispute_images')->nullable();        // array paths
            $table->timestamp('dispute_at')->nullable();
            $table->text('dispute_admin_notes')->nullable();
            $table->timestamp('dispute_resolved_at')->nullable();
            $table->foreignId('dispute_resolved_by')->nullable()->constrained('users')->nullOnDelete();

            // Status utama
            $table->enum('status', [
                'draft',
                'waiting_dp',
                'dp_uploaded',
                'dp_verified',
                'waiting_settlement',
                'settlement_uploaded',
                'settlement_verified',
                'in_transit',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->text('cancel_reason')->nullable();
            $table->text('notes')->nullable(); // catatan mitra saat order

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_orders');
    }
};
