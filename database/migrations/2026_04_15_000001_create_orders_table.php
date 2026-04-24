<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();          // APM-XXXX-TIMESTAMP
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('items');                         // cart snapshot
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Recipient info
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('recipient_city');
            $table->text('recipient_address');
            $table->text('notes')->nullable();

            // Payment
            $table->string('payment_method')->default('midtrans');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->text('midtrans_snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Order status
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
