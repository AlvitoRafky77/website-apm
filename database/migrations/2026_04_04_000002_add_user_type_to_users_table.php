<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['b2c', 'b2b'])->default('b2c')->after('address');
            $table->boolean('is_active')->default(true)->after('user_type');
            $table->unsignedBigInteger('b2b_registration_id')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'is_active', 'b2b_registration_id']);
        });
    }
};
