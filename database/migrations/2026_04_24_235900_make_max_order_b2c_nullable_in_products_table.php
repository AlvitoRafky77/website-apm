<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite does not support MODIFY or AFTER natively, but Laravel Schema handles modifications via change()
            // We need schema builder here
            Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('max_order_b2c')->nullable()->change();
            });
        } else {
            DB::statement('ALTER TABLE `products` MODIFY `max_order_b2c` INT NULL DEFAULT NULL AFTER `stock_b2b`');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('max_order_b2c')->nullable(false)->default(10)->change();
            });
        } else {
            DB::statement('ALTER TABLE `products` MODIFY `max_order_b2c` INT NOT NULL DEFAULT 10 AFTER `stock_b2b`');
        }
    }
};
