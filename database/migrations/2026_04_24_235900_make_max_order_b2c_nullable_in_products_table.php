<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `products` MODIFY `max_order_b2c` INT NULL DEFAULT NULL AFTER `stock_b2b`');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `products` MODIFY `max_order_b2c` INT NOT NULL DEFAULT 10 AFTER `stock_b2b`');
    }
};
