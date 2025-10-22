<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define Permissions (CRUD for your WorkUnit model)
        Permission::firstOrCreate(['name' => 'view work_units']);
        Permission::firstOrCreate(['name' => 'create work_units']);
        Permission::firstOrCreate(['name' => 'edit work_units']);
        Permission::firstOrCreate(['name' => 'delete work_units']);

        Permission::firstOrCreate(['name' => 'view inventory_asset_categories']);
        Permission::firstOrCreate(['name' => 'create inventory_asset_categories']);
        Permission::firstOrCreate(['name' => 'edit inventory_asset_categories']);
        Permission::firstOrCreate(['name' => 'delete inventory_asset_categories']);

        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        Permission::firstOrCreate(['name' => 'view inventory_assets']);
        Permission::firstOrCreate(['name' => 'create inventory_assets']);
        Permission::firstOrCreate(['name' => 'edit inventory_assets']);
        Permission::firstOrCreate(['name' => 'delete inventory_assets']);

        Permission::firstOrCreate(['name' => 'view permissions']);
        Permission::firstOrCreate(['name' => 'create permissions']);
        Permission::firstOrCreate(['name' => 'edit permissions']);
        Permission::firstOrCreate(['name' => 'delete permissions']);

        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'create roles']);
        Permission::firstOrCreate(['name' => 'edit roles']);
        Permission::firstOrCreate(['name' => 'delete roles']);

        // 3. Define Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor']);

        // 4. Assign Permissions to Roles
        $adminRole->givePermissionTo(Permission::all());

        $managerRole->givePermissionTo([
            'view inventory_assets',
            'create inventory_assets',
            'edit inventory_assets',
        ]);
        
        $supervisorRole->givePermissionTo('view inventory_assets');

        // 5. Assign a Role to a test user (optional)
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
