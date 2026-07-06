<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /**
     * TS.CHATBOT.001: Chatbot merespons pesan secara otomatis dengan format respons JSON valid
     */
    public function test_chatbot_returns_a_response(): void
    {
        $response = $this->postJson('/chatbot', [
            'message' => 'Halo, apakah produk APM sudah tersertifikasi halal?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }

    /**
     * TS.CONTACT.001 & TS.CONTACT.002: Mengirim pesan hubungi kami dengan nomor telepon valid (10-13 digit)
     */
    public function test_contact_form_submits_successfully_with_valid_data(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe Visitor',
            'company' => 'PT Makmur Jaya',
            'email' => 'visitor@example.com',
            'phone' => '081234567890', // 12 digits (valid)
            'topic' => 'Kemitraan Retail',
            'message' => 'Saya tertarik menjadi reseller produk beras dan minyak goreng APM.',
        ]);

        // Berhasil diarahkan kembali dengan pesan sukses
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe Visitor',
            'phone' => '081234567890',
        ]);
    }

    /**
     * TS.CONTACT.005: Form Hubungi Kami gagal karena nomor telepon kurang dari 10 digit (BVA)
     */
    public function test_contact_form_fails_when_phone_number_too_short(): void
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe Visitor',
            'email' => 'visitor@example.com',
            'phone' => '08123456', // 8 digits (invalid)
            'message' => 'Halo admin.',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    /**
     * TS.FINANCE.001: Validasi filter tanggal masa depan (di atas hari ini) pada Laporan Keuangan
     */
    public function test_finance_dashboard_fails_when_filter_date_is_in_the_future(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $tomorrow = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($admin)->get("/admin/finances?date_from={$tomorrow}&date_to={$tomorrow}");

        $response->assertSessionHasErrors(['date_from', 'date_to']);
    }

    /**
     * TS.FINANCE.002: Validasi filter tanggal melebihi 5 tahun ke belakang pada Laporan Keuangan
     */
    public function test_finance_dashboard_fails_when_filter_date_is_older_than_five_years(): void
    {
        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $sixYearsAgo = now()->subYears(6)->format('Y-m-d');

        $response = $this->actingAs($admin)->get("/admin/finances?date_from={$sixYearsAgo}&date_to={$sixYearsAgo}");

        $response->assertSessionHasErrors(['date_from', 'date_to']);
    }

    /**
     * Sc Req 001 & Sc Req 002: Memastikan pelanggan B2C ditolak (403) saat mencoba membuka B2B Contact
     */
    public function test_b2c_user_is_blocked_from_b2b_contact(): void
    {
        $b2cUser = User::factory()->create(['user_type' => 'b2c']);
        $b2cUser->assignRole('b2c_customer');

        $response = $this->actingAs($b2cUser)->get('/b2b/contact');
        $response->assertStatus(403);
    }

    /**
     * Sc Req 001 & Sc Req 002: Memastikan Mitra B2B terverifikasi diizinkan membuka halaman B2B Contact
     */
    public function test_b2b_user_can_access_b2b_contact(): void
    {
        $registration = \App\Models\B2bRegistration::create([
            'company_name' => 'Mitra B2B Baru',
            'company_type' => 'CV',
            'company_address' => 'Jl. Sudirman No. 10',
            'pic_name' => 'Sandi',
            'email' => 'sandi@mitrab2b.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'approved',
        ]);

        $b2bUser = User::factory()->create([
            'user_type' => 'b2b',
            'b2b_registration_id' => $registration->id,
        ]);
        $b2bUser->assignRole('b2b_customer');

        $response = $this->actingAs($b2bUser)->get('/b2b/contact');
        $response->assertStatus(200);
    }
}
