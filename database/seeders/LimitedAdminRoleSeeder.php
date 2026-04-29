<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class LimitedAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء role جديد مع صلاحية access dashboard فقط
        // Create new role with access dashboard permission only

        $limitedAdminRole = Role::firstOrCreate([
            'name' => 'limited_admin',
            'guard_name' => 'web',
        ]);

        // إعطاء صلاحية الدخول للداشبورد فقط تلقائياً
        // الصلاحيات الأخرى ستعطى يدوياً من الداشبورد
        // Give access dashboard permission automatically
        // Other permissions will be assigned manually from dashboard

        $accessDashboard = \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => 'access dashboard',
            'guard_name' => 'web',
        ]);

        $limitedAdminRole->givePermissionTo($accessDashboard);

        $this->command->info('✅ Limited Admin role created successfully!');
        $this->command->info('   This role has "access dashboard" permission by default.');
        $this->command->info('   Additional permissions must be assigned manually for each admin.');
    }
}
