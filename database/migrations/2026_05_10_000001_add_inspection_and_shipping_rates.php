<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Kolom inspeksi barang pada b2b_orders ────────────────────
        Schema::table('b2b_orders', function (Blueprint $table) {
            // Inspeksi saat barang tiba
            $table->string('inspection_status')->nullable()->after('delivery_notes');
            // 'good' = baik semua, 'damaged' = ada kerusakan
            $table->text('inspection_notes')->nullable()->after('inspection_status');
            $table->json('inspection_images')->nullable()->after('inspection_notes');
            $table->timestamp('inspected_at')->nullable()->after('inspection_images');
            $table->timestamp('arrived_at')->nullable()->after('inspected_at');
        });

        // ── Tabel tarif ongkir (jarak × berat) ──────────────────────
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city')->default('Pekanbaru');
            $table->string('destination_city');
            $table->decimal('rate_per_kg', 10, 2);    // Tarif per KG
            $table->decimal('min_weight_kg', 10, 2)->default(1000); // Min. order (1 ton)
            $table->integer('estimated_days')->nullable(); // Estimasi hari pengiriman
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['origin_city', 'destination_city']);
        });
    }

    public function down(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->dropColumn([
                'inspection_status',
                'inspection_notes',
                'inspection_images',
                'inspected_at',
                'arrived_at',
            ]);
        });

        Schema::dropIfExists('shipping_rates');
    }
};
