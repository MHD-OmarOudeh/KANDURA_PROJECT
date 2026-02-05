<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@kandura.com'],
            [
                'name' => 'Super Admin',
                'phone' => '0999999999',
                'password' => Hash::make('password'),
                'preferred_locale' => 'ar',
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@kandura.com'],
            [
                'name' => 'Admin',
                'phone' => '0988888888',
                'password' => Hash::make('password'),
                'preferred_locale' => 'ar',
            ]
        );
        $admin->assignRole('admin');

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@kandura.com'],
            [
                'name' => 'Regular User',
                'phone' => '0977777777',
                'password' => Hash::make('password'),
                'preferred_locale' => 'ar',
            ]
        );
        $user->assignRole('user');
        $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('Super Admin: superadmin@kandura.com / password');
        $this->command->info('Admin: admin@kandura.com / password');
        $this->command->info('User: user@kandura.com / password');
    }
}
