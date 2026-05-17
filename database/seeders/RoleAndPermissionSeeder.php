<?php
// database/seeders/RoleAndPermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage menus', 'manage categories', 'manage toppings', 'manage spiciness', 
            'manage tables', 'manage promos', 'manage orders', 'manage drivers', 
            'view reports', 'manage delivery settings', 'manage users',
            'pos access', 'view orders', 'view menus',
            'view kitchen', 'update order status',
            'view assigned orders', 'update delivery status'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Setup Roles & Permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);
        $kasirRole->givePermissionTo(['manage orders', 'view menus', 'manage tables', 'pos access', 'view orders']);

        $dapurRole = Role::firstOrCreate(['name' => 'Dapur']);
        $dapurRole->givePermissionTo(['view kitchen', 'update order status']);

        $driverRole = Role::firstOrCreate(['name' => 'Driver']);
        $driverRole->givePermissionTo(['view assigned orders', 'update delivery status']);
    }
}
