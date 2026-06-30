<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\B2bOrder;
use App\Models\B2bRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class B2BOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        Storage::fake('public');
    }

    /**
     * Mewakili: TS.ORDERB2B.001 s.d. TS.ORDERB2B.026 (Min Limit Order B2B 1 Ton)
     */
    public function test_b2b_order_item_fails_under_one_ton(): void
    {
        $user = User::factory()->create(['user_type' => 'b2b']);
        $user->assignRole('b2b_customer');
        $reg = B2bRegistration::create([
            'company_name' => 'PT Test Mitra',
            'company_type' => 'PT',
            'company_address' => 'Jl. Kebon Jeruk No 12',
            'pic_name' => 'Budi',
            'email' => $user->email,
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);
        $user->update(['b2b_registration_id' => $reg->id]);
        $user->refresh();

        $product = Product::factory()->create([
            'stock_b2b' => 20000,
            'price_b2b' => 10000,
        ]);

        // Kirim order 0.9 Ton (900 kg)
        $response = $this->actingAs($user)->post('/b2b/orders', [
            'products' => [
                [
                    'id' => $product->id,
                    'qty_ton' => 0.9, // Di bawah 1 ton (invalid)
                ]
            ],
            'recipient_name' => 'Warehouse A',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Hub',
            'recipient_city' => 'Pekanbaru',
        ]);

        $response->assertSessionHasErrors('products.0.qty_ton');
    }

    /**
     * Mewakili: TS.ORDERB2B.001 s.d. TS.ORDERB2B.026 & TS.INVOICE.001 s.d. TS.INVOICE.016 (B2B Order & Invoice DP 50%)
     */
    public function test_b2b_order_submits_successfully_calculating_dp(): void
    {
        $user = User::factory()->create(['user_type' => 'b2b']);
        $user->assignRole('b2b_customer');
        $reg = B2bRegistration::create([
            'company_name' => 'PT Test Mitra',
            'company_type' => 'PT',
            'company_address' => 'Jl. Kebon Jeruk No 12',
            'pic_name' => 'Budi',
            'email' => $user->email,
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);
        $user->update(['b2b_registration_id' => $reg->id]);

        $product = Product::factory()->create([
            'stock_b2b' => 20000,
            'price_b2b' => 10000, // Rp 10.000 per kg
        ]);

        // Order 2 Ton (2000 kg) -> Subtotal = Rp 20.000.000
        $response = $this->actingAs($user)->post('/b2b/orders', [
            'products' => [
                [
                    'id' => $product->id,
                    'qty_ton' => 2,
                ]
            ],
            'recipient_name' => 'Warehouse A',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Hub B',
            'recipient_city' => 'Pekanbaru',
        ]);

        $response->assertRedirectContains('/b2b/orders/');
        $this->assertDatabaseHas('b2b_orders', [
            'user_id' => $user->id,
            'subtotal' => 20000000, // 2000 * 10000
            'dp_amount' => 10000000, // 50%
            'status' => 'pending_review',
        ]);
    }

    /**
     * Mewakili: TS.VERORDERB2B.001 s.d. TS.VERORDERB2B.030 (Verifikasi & Input Ongkir)
     */
    public function test_admin_can_review_and_set_shipping_cost_for_b2b_order(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $reg = B2bRegistration::create([
            'company_name' => 'PT Test',
            'company_type' => 'PT',
            'company_address' => 'Jl. Test Address',
            'pic_name' => 'John',
            'email' => 'john@test.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);

        $order = B2bOrder::create([
            'order_number' => 'B2B-TEST-0001',
            'user_id' => User::factory()->create()->id,
            'b2b_registration_id' => $reg->id,
            'items' => [],
            'subtotal' => 20000000,
            'dp_amount' => 10000000,
            'total' => 20000000,
            'status' => 'pending_review',
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Merdeka',
            'recipient_city' => 'Pekanbaru',
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/b2b-orders')
            ->post("/admin/b2b-orders/{$order->order_number}/approve-review", [
                'action' => 'approve',
                'shipping_cost' => 500000, // Ongkir Rp 500.000
            ]);

        $response->assertRedirect('/admin/b2b-orders');
        
        $order->refresh();
        $this->assertEquals('waiting_dp', $order->status);
        $this->assertEquals(500000, $order->shipping_cost);
        // Total baru = subtotal + ongkir = 20.500.000
        $this->assertEquals(20500000, $order->total);
        // DP baru = 50% dari total baru = 10.250.000
        $this->assertEquals(10250000, $order->dp_amount);
    }

    /**
     * Mewakili: TS.VERORDERB2B.001 s.d. TS.VERORDERB2B.030 (Dispatch Armada Driver B2B)
     */
    public function test_admin_can_dispatch_armada_for_b2b_order(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $reg = B2bRegistration::create([
            'company_name' => 'PT Test 2',
            'company_type' => 'PT',
            'company_address' => 'Jl. Test Address',
            'pic_name' => 'John',
            'email' => 'john2@test.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);

        $order = B2bOrder::create([
            'order_number' => 'B2B-TEST-0002',
            'user_id' => User::factory()->create()->id,
            'b2b_registration_id' => $reg->id,
            'items' => [],
            'subtotal' => 20000000,
            'total' => 20000000,
            'status' => 'dp_verified', // DP sudah lunas diverifikasi
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Merdeka',
            'recipient_city' => 'Pekanbaru',
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/b2b-orders')
            ->post("/admin/b2b-orders/{$order->order_number}/dispatch-armada", [
                'delivery_date' => now()->format('Y-m-d'),
                'driver_name' => 'Doni Driver',
                'driver_phone' => '081234567890', // Valid 12 digit
                'waybill_number' => 'SPB-12345',
            ]);

        $response->assertRedirect('/admin/b2b-orders');
        
        $order->refresh();
        $this->assertEquals('in_transit', $order->status);
        $this->assertEquals('Doni Driver', $order->driver_name);
        $this->assertEquals('081234567890', $order->driver_phone);
    }

    /**
     * Mewakili: TS.VERORDERB2B.001 s.d. TS.VERORDERB2B.030 (Input Potongan Ganti Rugi Kerusakan)
     */
    public function test_admin_can_set_damage_deduction(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $reg = B2bRegistration::create([
            'company_name' => 'PT Test 3',
            'company_type' => 'PT',
            'company_address' => 'Jl. Test Address',
            'pic_name' => 'John',
            'email' => 'john3@test.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);

        $order = B2bOrder::create([
            'order_number' => 'B2B-TEST-0003',
            'user_id' => User::factory()->create()->id,
            'b2b_registration_id' => $reg->id,
            'items' => [],
            'subtotal' => 20500000,
            'total' => 20500000,
            'dp_amount' => 10250000,
            'settlement_amount' => 10250000, // sisa 50% pelunasan
            'status' => 'goods_accepted',
            'inspection_status' => 'damaged', // barang dilaporkan rusak oleh mitra
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'recipient_address' => 'Jl. Merdeka',
            'recipient_city' => 'Pekanbaru',
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/b2b-orders')
            ->post("/admin/b2b-orders/{$order->order_number}/set-damage-deduction", [
                'damage_deduction' => 1000000, // potongan Rp 1.000.000
                'damage_notes' => 'Gula basah 2 karung',
            ]);

        $response->assertRedirect('/admin/b2b-orders');
        
        $order->refresh();
        $this->assertEquals(1000000, $order->damage_deduction);
        // Sisa pelunasan setelah diskon = 10.250.000 - 1.000.000 = 9.250.000
        $this->assertEquals(9250000, $order->adjusted_settlement);
    }
}
