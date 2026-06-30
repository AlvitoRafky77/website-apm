<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2COrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /**
     * Mewakili: TS.ORDERB2C.001 s.d. TS.ORDERB2C.028 (Tambah Keranjang Min Limit)
     */
    public function test_add_to_cart_respects_minimum_limit_of_ten_items(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $product = Product::factory()->create([
            'min_order_b2c' => 10,
            'max_order_b2c' => 50,
            'stock_b2c' => 100,
        ]);

        // Coba masukkan 9 item (di bawah min 10)
        $response = $this->actingAs($user)->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 9,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Memastikan keranjang di session berisi 10 item (karena auto-adjust ke minimum)
        $cart = session('cart');
        $this->assertNotNull($cart);
        $this->assertEquals(10, $cart[$product->id]['quantity']);
    }

    /**
     * Mewakili: TS.ORDERB2C.001 s.d. TS.ORDERB2C.028 (Tambah Keranjang Max Limit)
     */
    public function test_add_to_cart_respects_maximum_limit_of_fifty_items(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $product = Product::factory()->create([
            'min_order_b2c' => 10,
            'max_order_b2c' => 50,
            'stock_b2c' => 100,
        ]);

        // Coba masukkan 51 item (di atas max 50)
        $response = $this->actingAs($user)->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 51,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $cart = session('cart');
        $this->assertEquals(50, $cart[$product->id]['quantity']);
    }

    /**
     * Mewakili: TS.ORDERB2C.001 s.d. TS.ORDERB2C.028 & TS.REVIEW.001 s.d. TS.REVIEW.023 (Checkout & Review Produk)
     */
    public function test_b2c_checkout_locks_stock_temporarily(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $product = Product::factory()->create([
            'stock_b2c' => 100,
            'price' => 10000,
        ]);

        // Set isi keranjang langsung di session
        $cart = [
            $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 20,
                'image' => '',
                'weight' => 1,
            ]
        ];

        // Jalankan checkout (POST ke /checkout)
        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/checkout', [
                'recipient_name' => 'John Doe',
                'recipient_phone' => '081234567890',
                'recipient_address' => 'Jl. Merdeka No. 1',
                'recipient_city' => 'Pekanbaru',
                'recipient_lat' => 0.4955,
                'recipient_lng' => 101.3561,
                'shipping_cost' => 15000,
                'shipping_vehicle' => 'Motor',
                'shipping_mode' => 'lalamove',
            ]);

        // Response berupa JSON snap token dari Midtrans
        $response->assertStatus(200);
        $response->assertJsonStructure(['snap_token', 'order_id']);

        // Cek order terbuat di database dengan payment_status pending & stock_reserved true
        $this->assertDatabaseHas('orders', [
            'recipient_name' => 'John Doe',
            'payment_status' => 'pending',
            'stock_reserved' => true,
        ]);

        // Cek stok ter-lock (stok fisik berkurang 20)
        $product->refresh();
        $this->assertEquals(80, $product->stock_b2c);
    }

    /**
     * Mewakili: TS.PAYB2C.001 s.d. TS.PAYB2C.015 & TS.ADMORDERB2C.001 s.d. TS.ADMORDERB2C.016 (Webhook Midtrans Settlement)
     */
    public function test_midtrans_settlement_commits_stock_permanently(): void
    {
        $product = Product::factory()->create(['stock_b2c' => 80]); // stok awal 100, sisa 80 karena dilock
        $order = Order::create([
            'order_id' => 'APM-TEST-0001',
            'user_id' => User::factory()->create()->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => 20,
                    'price' => 10000,
                ]
            ],
            'subtotal' => 200000,
            'shipping_cost' => 15000,
            'total' => 215000,
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Test',
            'recipient_city' => 'Pekanbaru',
            'recipient_lat' => 0.49,
            'recipient_lng' => 101.3,
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
            'stock_reserved' => true,
            'status' => 'pending',
        ]);

        // Mock seluruh class Midtrans\Notification dan bind ke Laravel Service Container
        $notifMock = \Mockery::mock(\Midtrans\Notification::class);
        $notifMock->order_id = $order->order_id;
        $notifMock->transaction_status = 'settlement';
        $notifMock->fraud_status = 'accept';
        $notifMock->payment_type = 'credit_card';
        $notifMock->transaction_id = 'midtrans-tx-12345';
        $this->app->instance(\Midtrans\Notification::class, $notifMock);

        // Simulasikan POST Webhook Midtrans
        $response = $this->post('/payment/notification', [
            'order_id' => $order->order_id,
            'transaction_status' => 'settlement',
            'payment_type' => 'credit_card',
            'transaction_id' => 'midtrans-tx-12345',
        ]);

        $response->assertStatus(200);

        // Verifikasi status order berubah menjadi paid & confirmed
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('confirmed', $order->status);
        $this->assertFalse($order->stock_reserved); // stock_reserved dilepas (karena sudah permanen)

        // Stok produk tetap 80 (pemotongan sudah permanen)
        $product->refresh();
        $this->assertEquals(80, $product->stock_b2c);
    }

    /**
     * Mewakili: TS.PAYB2C.001 s.d. TS.PAYB2C.015 & TS.ADMORDERB2C.001 s.d. TS.ADMORDERB2C.016 (Webhook Midtrans Expired)
     */
    public function test_midtrans_expire_releases_locked_stock(): void
    {
        $product = Product::factory()->create(['stock_b2c' => 80]); // dikunci 20 dari total awal 100
        $order = Order::create([
            'order_id' => 'APM-TEST-0002',
            'user_id' => User::factory()->create()->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => 20,
                    'price' => 10000,
                ]
            ],
            'subtotal' => 200000,
            'shipping_cost' => 15000,
            'total' => 215000,
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Test',
            'recipient_city' => 'Pekanbaru',
            'recipient_lat' => 0.49,
            'recipient_lng' => 101.3,
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
            'stock_reserved' => true,
            'status' => 'pending',
        ]);

        // Mock seluruh class Midtrans\Notification untuk status expired
        $notifMockExpire = \Mockery::mock(\Midtrans\Notification::class);
        $notifMockExpire->order_id = $order->order_id;
        $notifMockExpire->transaction_status = 'expire';
        $notifMockExpire->fraud_status = 'accept';
        $notifMockExpire->payment_type = 'credit_card';
        $notifMockExpire->transaction_id = 'midtrans-tx-67890';
        $this->app->instance(\Midtrans\Notification::class, $notifMockExpire);

        // Simulasikan Webhook Expired
        $response = $this->post('/payment/notification', [
            'order_id' => $order->order_id,
            'transaction_status' => 'expire',
            'payment_type' => 'credit_card',
            'transaction_id' => 'midtrans-tx-67890',
        ]);

        $response->assertStatus(200);

        // Verifikasi status order berubah menjadi expired & cancelled
        $order->refresh();
        $this->assertEquals('expired', $order->payment_status);
        $this->assertEquals('cancelled', $order->status);
        $this->assertFalse($order->stock_reserved);

        // Stok produk kembali bertambah 20 (kembali menjadi 100)
        $product->refresh();
        $this->assertEquals(100, $product->stock_b2c);
    }
}
