<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya tambah shipping_mode — kolom lain (courier, tracking_number, shipped_at) sudah ada
            if (!Schema::hasColumn('orders', 'shipping_mode')) {
                $table->string('shipping_mode')->nullable()->default('lalamove')->after('shipping_vehicle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_mode')) {
                $table->dropColumn('shipping_mode');
            }
        });
    }
};
