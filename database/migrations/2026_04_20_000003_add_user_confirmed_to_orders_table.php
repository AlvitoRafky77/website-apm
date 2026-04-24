<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('user_confirmed')->default(false)->after('shipped_at');
            $table->timestamp('user_confirmed_at')->nullable()->after('user_confirmed');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['user_confirmed', 'user_confirmed_at']);
        });
    }
};
