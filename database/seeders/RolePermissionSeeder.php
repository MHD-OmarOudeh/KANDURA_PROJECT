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


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }


        Role::create(['name' => 'guest', 'guard_name' => 'web']);


        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'view addresses', 'create addresses', 'edit addresses', 'delete addresses',
            'view orders', 'create orders',
            'view designs', 'create designs', 'edit designs', 'delete designs',
            'view wallet', 'validate coupons',
            'view reviews', 'create reviews',
            'view notifications',
            'view measurements', 'create measurements', 'edit measurements', 'delete measurements',
            'view design options', 'view invoices',
        ]);


        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'view users', 'manage users',
            'manage all addresses',
            'view orders', 'manage all orders', 'update order status',
            'view designs', 'manage all designs',
            'manage wallet', 'deposit wallet', 'withdraw wallet',
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',
            'view reviews', 'manage reviews', 'approve reviews',
            'view notifications', 'send notifications', 'manage notifications',
            'view design options', 'manage design options',
            'view invoices', 'manage invoices',
            'access dashboard', 'view reports',
        ]);

        
        $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        $this->command->info('✅ Roles and Permissions created successfully!');
    }
}














