<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita ubah menjadi string sementara agar fleksibel, 
        // atau jika ingin tetap enum kita harus definisikan ulang semua opsinya.
        // Di Laravel, mengubah ENUM paling aman adalah menggunakan DB::statement 
        // atau mengubahnya menjadi string jika ingin lebih mudah di masa depan.
        
        Schema::table('b2b_orders', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('b2b_orders', function (Blueprint $table) {
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
            ])->default('draft')->change();
        });
    }
};
