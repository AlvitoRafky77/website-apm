<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\B2bRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRoles();
    }

    /**
     * TS.LOGIN.001: Login menggunakan email dan password valid sebagai B2C
     */
    public function test_b2c_user_can_login_with_valid_credentials(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'user_type' => 'b2c',
            'is_active' => true,
        ]);
        $user->assignRole('b2c_customer');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    /**
     * TS.LOGIN.002: Login menggunakan email dan password valid sebagai B2B
     */
    public function test_b2b_user_can_login_with_valid_credentials(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'user_type' => 'b2b',
            'is_active' => true,
        ]);
        $user->assignRole('b2b_customer');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/b2b/dashboard');
    }

    /**
     * TS.LOGIN.003: Login menggunakan email dan password valid sebagai Admin
     */
    public function test_admin_user_can_login_with_valid_credentials(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'user_type' => 'b2c', // must be B2C or B2B to satisfy the DB constraint, roles decide admin permission
            'is_active' => true,
        ]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * TS.LOGIN.004: Login dengan format email tidak valid
     */
    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email-format',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TS.LOGIN.005: Login dengan email kosong
     */
    public function test_login_fails_with_empty_email(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TS.LOGIN.006: Login dengan password kosong
     */
    public function test_login_fails_with_empty_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * Mewakili: TS.LOGIN.007 (Password Salah) & TS.LOGIN.008 (Email Belum Terdaftar)
     */
    public function test_login_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
            'user_type' => 'b2c',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Mewakili: TS.LOGOUT.001 s.d. TS.LOGOUT.003 (Logout Pengguna B2C, B2B, Admin)
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * Mewakili: TS.LOGOUT.004 s.d. TS.LOGOUT.007 (Keamanan & Akses Halaman Pasca Logout)
     */
    public function test_cannot_access_profile_after_logout(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $this->actingAs($user)->post('/logout');

        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /**
     * Mewakili: TS.PROFILE.001 & TS.PROFILE.002 (Update Informasi & Kontak Profil)
     */
    public function test_b2c_profile_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'phone' => '08123456789',
            'user_type' => 'b2c',
        ]);
        $user->assignRole('b2c_customer');

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '081234567890', // 12 digits (valid)
            'address' => 'New Address',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('081234567890', $user->phone);
    }

    /**
     * TS.PROFILE.003: Validasi nomor telepon kurang dari 10 digit (BVA)
     */
    public function test_profile_update_fails_with_phone_number_too_short(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567', // 9 digits (invalid)
        ]);

        $response->assertSessionHasErrors('phone');
    }

    /**
     * TS.PROFILE.003: Validasi nomor telepon lebih dari 13 digit (BVA)
     */
    public function test_profile_update_fails_with_phone_number_too_long(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '08123456789012', // 14 digits (invalid)
        ]);

        $response->assertSessionHasErrors('phone');
    }

    /**
     * TS.PROFILE.003: Validasi nomor telepon tidak boleh berisi karakter non-angka
     */
    public function test_profile_update_fails_with_non_numeric_phone(): void
    {
        $user = User::factory()->create(['user_type' => 'b2c']);
        $user->assignRole('b2c_customer');

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0812abc5678', // non-numeric (invalid)
        ]);

        $response->assertSessionHasErrors('phone');
    }
}
