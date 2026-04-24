<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->string('dp_bank_name')->nullable()->after('dp_proof_path');
            $table->string('dp_transfer_reference')->nullable()->after('dp_bank_name');
            $table->string('settlement_bank_name')->nullable()->after('settlement_proof_path');
            $table->string('settlement_transfer_reference')->nullable()->after('settlement_bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->dropColumn([
                'dp_bank_name',
                'dp_transfer_reference',
                'settlement_bank_name',
                'settlement_transfer_reference',
            ]);
        });
    }
};
