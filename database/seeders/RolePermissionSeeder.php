<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // حذف كل الـ Roles والـ Permissions القديمة
        Permission::query()->delete();
        Role::query()->delete();

        $permissions = [
            'view users', 'create users', 'edit users', 'delete users', 'manage users',
            'view addresses', 'create addresses', 'edit addresses', 'delete addresses', 'manage all addresses',
            'view orders', 'create orders', 'edit orders', 'delete orders', 'manage all orders', 'update order status',
            'view designs', 'create designs', 'edit designs', 'delete designs', 'manage all designs',
            'view wallet', 'manage wallet', 'deposit wallet', 'withdraw wallet',
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons', 'validate coupons',
            'view reviews', 'create reviews', 'manage reviews', 'approve reviews',
            'view notifications', 'send notifications', 'manage notifications',
            'view measurements', 'create measurements', 'edit measurements', 'delete measurements',
            'view design options', 'manage design options',
            'view invoices', 'manage invoices',
            'access dashboard',
            'manage settings', 'view reports', 'manage roles',
        ];


        $permissionModels = [];
        foreach ($permissions as $permission) {
            $permissionModels[$permission] = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        Role::create(['name' => 'guest', 'guard_name' => 'web']);


        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            $permissionModels['view addresses'],
            $permissionModels['create addresses'],
            $permissionModels['edit addresses'],
            $permissionModels['delete addresses'],
            $permissionModels['view orders'],
            $permissionModels['create orders'],
            $permissionModels['view designs'],
            $permissionModels['create designs'],
            $permissionModels['edit designs'],
            $permissionModels['delete designs'],
            $permissionModels['view wallet'],
            $permissionModels['validate coupons'],
            $permissionModels['view reviews'],
            $permissionModels['create reviews'],
            $permissionModels['view notifications'],
            $permissionModels['view measurements'],
            $permissionModels['create measurements'],
            $permissionModels['edit measurements'],
            $permissionModels['delete measurements'],
            $permissionModels['view design options'],
            $permissionModels['view invoices'],
        ]);


        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            $permissionModels['view users'],
            $permissionModels['manage users'],
            $permissionModels['manage all addresses'],
            $permissionModels['view orders'],
            $permissionModels['manage all orders'],
            $permissionModels['update order status'],
            $permissionModels['view designs'],
            $permissionModels['manage all designs'],
            $permissionModels['manage wallet'],
            $permissionModels['deposit wallet'],
            $permissionModels['withdraw wallet'],
            $permissionModels['view coupons'],
            $permissionModels['create coupons'],
            $permissionModels['edit coupons'],
            $permissionModels['delete coupons'],
            $permissionModels['view reviews'],
            $permissionModels['manage reviews'],
            $permissionModels['approve reviews'],
            $permissionModels['view notifications'],
            $permissionModels['send notifications'],
            $permissionModels['manage notifications'],
            $permissionModels['view design options'],
            $permissionModels['manage design options'],
            $permissionModels['view invoices'],
            $permissionModels['manage invoices'],
            $permissionModels['access dashboard'],
            $permissionModels['view reports'],
        ]);


        $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        $this->command->info('✅ Roles and Permissions created successfully!');
    }
}














