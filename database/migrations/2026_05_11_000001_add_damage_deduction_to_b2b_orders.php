<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->decimal('damage_deduction', 15, 2)->default(0)->after('settlement_amount');
            $table->text('damage_notes')->nullable()->after('damage_deduction');
            $table->unsignedBigInteger('damage_set_by')->nullable()->after('damage_notes');
            $table->timestamp('damage_set_at')->nullable()->after('damage_set_by');
        });
    }

    public function down(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->dropColumn(['damage_deduction', 'damage_notes', 'damage_set_by', 'damage_set_at']);
        });
    }
};
