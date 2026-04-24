<?php

namespace Database\Seeders;

use App\Models\B2bRegistration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        $this->call([
            RoleAndPermissionSeeder::class,
            AdminSeeder::class,
            ProductSeeder::class,
            ProductReviewSeeder::class,
        ]);

        $b2bAccounts = [
            [
                'email' => 'b2b.demo@apm.com',
                'name' => 'Mitra Demo B2B',
                'company_name' => 'PT Demo Mitra Pangan',
                'company_type' => 'PT',
                'npwp' => '01.234.567.8-999.000',
                'company_address' => 'Jl. Industri Pangan No. 88, Jakarta Timur',
                'company_city' => 'Jakarta',
                'company_province' => 'DKI Jakarta',
                'company_postal_code' => '13340',
                'pic_name' => 'Dina Pramesti',
                'pic_position' => 'Procurement Manager',
                'phone' => '081234567890',
            ],
            [
                'email' => 'maszaser74@gmail.com',
                'name' => 'Mas Zaser B2B',
                'company_name' => 'CV Maz Zaser Pangan',
                'company_type' => 'CV',
                'npwp' => '02.345.678.9-111.000',
                'company_address' => 'Jl. Distribusi Raya No. 74, Bandung',
                'company_city' => 'Bandung',
                'company_province' => 'Jawa Barat',
                'company_postal_code' => '40123',
                'pic_name' => 'Mas Zaser',
                'pic_position' => 'Owner',
                'phone' => '081298765432',
            ],
        ];

        foreach ($b2bAccounts as $account) {
            $registration = B2bRegistration::updateOrCreate(
                ['email' => $account['email']],
                [
                    'company_name' => $account['company_name'],
                    'company_type' => $account['company_type'],
                    'npwp' => $account['npwp'],
                    'company_address' => $account['company_address'],
                    'company_city' => $account['company_city'],
                    'company_province' => $account['company_province'],
                    'company_postal_code' => $account['company_postal_code'],
                    'pic_name' => $account['pic_name'],
                    'pic_position' => $account['pic_position'],
                    'email' => $account['email'],
                    'phone' => $account['phone'],
                    'password' => Hash::make('b2btesting123'),
                    'status' => 'approved',
                    'approved_at' => now(),
                    'admin_notes' => 'Akun B2B untuk verifikasi portal.',
                ]
            );

            $b2bUser = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'password' => Hash::make('b2btesting123'),
                    'user_type' => 'b2b',
                    'is_active' => true,
                    'b2b_registration_id' => $registration->id,
                ]
            );

            $registration->update(['user_id' => $b2bUser->id]);
            $b2bUser->assignRole('b2b_customer');
        }
    }
}
