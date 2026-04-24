<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add weight to products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->default(1.00)->after('unit'); // KG per unit
        });

        // Add Lalamove & coordinate fields to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('recipient_lat', 10, 7)->nullable()->after('recipient_address');
            $table->decimal('recipient_lng', 11, 7)->nullable()->after('recipient_lat');
            $table->string('shipping_vehicle')->nullable()->after('shipping_cost');
            $table->string('lalamove_quotation_id')->nullable()->after('shipped_at');
            $table->string('lalamove_order_id')->nullable()->after('lalamove_quotation_id');
            $table->string('lalamove_status')->nullable()->after('lalamove_order_id');
            $table->string('lalamove_driver_name')->nullable()->after('lalamove_status');
            $table->string('lalamove_driver_phone')->nullable()->after('lalamove_driver_name');
            $table->string('lalamove_share_link')->nullable()->after('lalamove_driver_phone');
            $table->boolean('stock_reserved')->default(false)->after('lalamove_share_link');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_lat', 'recipient_lng', 'shipping_vehicle',
                'lalamove_quotation_id', 'lalamove_order_id', 'lalamove_status',
                'lalamove_driver_name', 'lalamove_driver_phone', 'lalamove_share_link',
                'stock_reserved',
            ]);
        });
    }
};
