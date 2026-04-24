<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('settlement_at')->nullable()->after('paid_at');
            $table->string('settlement_status')->nullable()->after('settlement_at');
            $table->decimal('refund_amount', 12, 2)->default(0)->after('settlement_status');
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');
            $table->text('refund_reason')->nullable()->after('refunded_at');
            $table->json('midtrans_status_payload')->nullable()->after('refund_reason');
            $table->timestamp('midtrans_sync_at')->nullable()->after('midtrans_status_payload');

            $table->index(['payment_status', 'paid_at']);
            $table->index(['midtrans_payment_type', 'payment_status']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('finance_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->string('status')->default('running');
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('synced_orders')->default(0);
            $table->unsignedInteger('failed_orders')->default(0);
            $table->text('notes')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_sync_runs');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'paid_at']);
            $table->dropIndex(['midtrans_payment_type', 'payment_status']);
            $table->dropIndex(['status', 'created_at']);

            $table->dropColumn([
                'settlement_at',
                'settlement_status',
                'refund_amount',
                'refunded_at',
                'refund_reason',
                'midtrans_status_payload',
                'midtrans_sync_at',
            ]);
        });
    }
};
