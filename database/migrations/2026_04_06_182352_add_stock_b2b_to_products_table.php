<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stok khusus B2B — partai besar (satuan dalam KG/Unit sesuai unit produk)
            $table->integer('stock_b2b')->default(0)->after('stock_b2c');
            // Batas maksimal pembelian B2C dalam KG (default 10)
            $table->integer('max_order_b2c')->default(10)->after('stock_b2b');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_b2b', 'max_order_b2c']);
        });
    }
};
