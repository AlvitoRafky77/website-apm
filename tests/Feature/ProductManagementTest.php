<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        Storage::fake('public');
    }

    /**
     * TS.KATALOG.004: Validasi produk aktif tampil di halaman katalog (produk tidak aktif disembunyikan)
     */
    public function test_katalog_shows_active_products_only(): void
    {
        $activeProduct = Product::factory()->create(['is_active' => true]);
        $inactiveProduct = Product::factory()->create(['is_active' => false]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee($activeProduct->name);
        $response->assertDontSee($inactiveProduct->name);
    }

    /**
     * TS.KATALOG.008: Validasi harga retail (B2C) & harga kontrak mitra (B2B) tampil di detail produk
     */
    public function test_product_detail_displays_correct_prices(): void
    {
        $product = Product::factory()->create([
            'price' => 15000,
            'price_b2b' => 12000,
            'is_active' => true,
        ]);

        // Guest / B2C User
        $response = $this->get("/product/{$product->slug}");
        $response->assertStatus(200);
        $response->assertSee('15.000');

        // B2B User
        $b2bUser = User::factory()->create(['user_type' => 'b2b']);
        $b2bUser->assignRole('b2b_customer');

        $responseB2b = $this->actingAs($b2bUser)->get("/b2b/dashboard"); // Cek harga B2B di dashboard/detail
        $responseB2b->assertStatus(200);
        $responseB2b->assertSee('12.000');
    }

    /**
     * TS.PRODUK.002: Admin dapat menambahkan produk baru dengan data lengkap & valid
     */
    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->image('gula.jpg');

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Gula Pasir Premium',
            'category_id' => $category->id,
            'price' => '18000',
            'price_b2b' => '16000',
            'min_order_b2c' => 10,
            'max_order_b2c' => 50,
            'min_order_b2b' => 1000,
            'max_order_b2b' => 10000,
            'unit' => 'kg',
            'weight' => 1,
            'stock_b2c' => 100,
            'stock_b2b' => 5000,
            'description' => 'Gula pasir tebu asli pilihan.',
            'image' => $image,
            'is_active' => 1,
            'is_featured' => 0,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Gula Pasir Premium',
            'price' => 18000,
            'stock_b2c' => 100,
        ]);
    }

    /**
     * TS.PRODUK.008: Validasi input jumlah stok produk tidak boleh bernilai negatif
     */
    public function test_create_product_fails_with_negative_stock(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Gula Pasir Premium',
            'category_id' => $category->id,
            'price' => '18000',
            'price_b2b' => '16000',
            'stock_b2c' => -5, // negatif (invalid)
            'stock_b2b' => 5000,
            'unit' => 'kg',
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors('stock_b2c');
    }

    /**
     * TS.KATEGORI.002: Admin dapat membuat kategori produk baru
     */
    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Bahan Sembako',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Bahan Sembako',
        ]);
    }

    /**
     * TS.KATEGORI.010: Admin dapat menghapus kategori produk terdaftar
     */
    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/categories/{$category->id}");

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
