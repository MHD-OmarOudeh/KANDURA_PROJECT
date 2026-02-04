# Create Super Admin using Laravel Tinker

## Open Tinker
```bash
php artisan tinker
```

## Create Super Admin User
```php
// Create the user
$user = \App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'superadmin@kandura.com',
    'phone' => '0501234567',
    'password' => bcrypt('password123'),
    'is_active' => true,
]);

// Assign super-admin role
$user->assignRole('super-admin');

// Give all permissions to super admin
$user->givePermissionTo(\Spatie\Permission\Models\Permission::all());

echo "Super Admin created successfully!\n";
echo "Email: superadmin@kandura.com\n";
echo "Password: password123\n";
```

## Alternative: One-line command
```php
$user = \App\Models\User::create(['name' => 'Super Admin', 'email' => 'superadmin@kandura.com', 'phone' => '0501234567', 'password' => bcrypt('password123'), 'is_active' => true]); $user->assignRole('super-admin'); $user->givePermissionTo(\Spatie\Permission\Models\Permission::all());
```

## If roles don't exist, create them first:
```php
// Create roles
\Spatie\Permission\Models\Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
\Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
\Spatie\Permission\Models\Role::create(['name' => 'user', 'guard_name' => 'web']);
```

## Exit Tinker
```php
exit
```
