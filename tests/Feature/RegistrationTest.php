<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\B2bRegistration;
use App\Mail\B2bAccountApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
        Storage::fake('public');
    }

    /**
     * Mewakili: TS.REGB2C.001 (Registrasi Valid) & TS.REGB2C.005 (BVA Nomor Telepon 10 s.d. 13 Digit)
     */
    public function test_b2c_registration_successful_with_valid_data(): void
    {
        $response = $this->post('/register/b2c', [
            'name' => 'B2C Client',
            'email' => 'clientb2c@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'clientb2c@example.com',
            'user_type' => 'b2c',
        ]);
        $response->assertRedirect('/');
    }

    /**
     * TS.REGB2C.006: Validasi nomor telepon kurang dari 10 digit (BVA)
     */
    public function test_b2c_registration_fails_with_phone_number_too_short(): void
    {
        $response = $this->post('/register/b2c', [
            'name' => 'B2C Client',
            'email' => 'clientb2c@example.com',
            'phone' => '081234567', // 9 digits (invalid)
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('phone');
        $this->assertGuest();
    }

    /**
     * TS.REGB2C.006: Validasi nomor telepon lebih dari 13 digit (BVA)
     */
    public function test_b2c_registration_fails_with_phone_number_too_long(): void
    {
        $response = $this->post('/register/b2c', [
            'name' => 'B2C Client',
            'email' => 'clientb2c@example.com',
            'phone' => '08123456789012', // 14 digits (invalid)
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('phone');
        $this->assertGuest();
    }

    /**
     * Mewakili: TS.REGB2B.001 s.d. TS.REGB2B.016 (Registrasi B2B Valid)
     */
    public function test_b2b_registration_submits_successfully_with_files(): void
    {
        $companyProfile = UploadedFile::fake()->create('profile.pdf', 1000); // 1MB
        $siup = UploadedFile::fake()->create('siup.png', 500); // 500KB

        $response = $this->post('/register/b2b', [
            'company_name' => 'PT Agro Mitra',
            'company_type' => 'PT',
            'npwp' => '12.345.678.9-123.000',
            'company_address' => 'Jl. Kebon Jeruk No. 12',
            'company_city' => 'Jakarta',
            'company_province' => 'DKI Jakarta',
            'pic_name' => 'Budi Sudarsono',
            'pic_position' => 'Direktur Utama',
            'email' => 'budi@agromitra.com',
            'phone' => '0812987654321', // 13 digits (valid)
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company_profile' => $companyProfile,
            'siup' => $siup,
            'agree_terms' => '1',
        ]);

        // Registrasi B2B tidak login otomatis, tapi diarahkan ke halaman pending
        $this->assertGuest();
        $this->assertDatabaseHas('b2b_registrations', [
            'company_name' => 'PT Agro Mitra',
            'email' => 'budi@agromitra.com',
            'status' => 'pending',
        ]);
        $response->assertRedirect('/register/b2b/pending');
    }

    /**
     * TS.REGB2B.017: Validasi ukuran dokumen legalitas melebihi batas maksimal (5MB)
     */
    public function test_b2b_registration_fails_when_file_exceeds_5mb(): void
    {
        $largeProfile = UploadedFile::fake()->create('profile.pdf', 6000); // ~6MB (invalid)

        $response = $this->post('/register/b2b', [
            'company_name' => 'PT Agro Mitra',
            'company_type' => 'PT',
            'company_address' => 'Jl. Kebon Jeruk No. 12',
            'pic_name' => 'Budi Sudarsono',
            'email' => 'budi@agromitra.com',
            'phone' => '0812987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company_profile' => $largeProfile,
            'agree_terms' => '1',
        ]);

        $response->assertSessionHasErrors('company_profile');
        $this->assertGuest();
    }

    /**
     * TS.VERB2B.003: Admin menyetujui (approve) registrasi B2B & mengirim email
     */
    public function test_admin_can_approve_b2b_registration(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['user_type' => 'b2c']);
        $admin->assignRole('admin');

        $registration = B2bRegistration::create([
            'company_name' => 'Mitra B2B Baru',
            'company_type' => 'CV',
            'company_address' => 'Jl. Sudirman No. 10',
            'pic_name' => 'Sandi',
            'email' => 'sandi@mitrab2b.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/b2b-registrations')
            ->post("/admin/b2b-registrations/{$registration->id}/approve");

        $response->assertRedirect('/admin/b2b-registrations');
        
        // Cek data registrasi terupdate
        $registration->refresh();
        $this->assertEquals('approved', $registration->status);
        $this->assertNotNull($registration->approved_at);

        // Cek akun user baru terbentuk di database
        $this->assertDatabaseHas('users', [
            'email' => 'sandi@mitrab2b.com',
            'user_type' => 'b2b',
            'is_active' => true,
        ]);

        // Cek email notifikasi terkirim
        Mail::assertSent(B2bAccountApproved::class, function ($mail) use ($registration) {
            return $mail->hasTo('sandi@mitrab2b.com');
        });
    }

    /**
     * TS.VERB2B.010: Pengguna non-admin ditolak saat mencoba approve B2B
     */
    public function test_non_admin_cannot_approve_b2b_registration(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $registration = B2bRegistration::create([
            'company_name' => 'Mitra B2B Baru',
            'company_type' => 'CV',
            'company_address' => 'Jl. Sudirman No. 10',
            'pic_name' => 'Sandi',
            'email' => 'sandi@mitrab2b.com',
            'phone' => '081234567890',
            'password' => bcrypt('password123'),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post("/admin/b2b-registrations/{$registration->id}/approve");

        $response->assertStatus(403); // Forbidden
        $this->assertEquals('pending', $registration->fresh()->status);
    }
}
