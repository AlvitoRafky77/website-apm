<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Buat atau update akun admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@apm.com'],
            [
                'name'     => 'Admin APM',
                'email'    => 'admin@apm.com',
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign role admin
        $admin->assignRole($adminRole);

        $this->command->info('✅ Admin seeder berhasil! Email: admin@apm.com | Password: admin123');
    }
}
