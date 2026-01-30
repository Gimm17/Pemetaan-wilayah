<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Define Permissions ---
        $permissions = [
            'admin.users.manage',
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',      // Hapus satu/satu
            'locations.delete_all',  // Hapus semua / bulk delete
            'locations.approve',
            'locations.submit',
            'exports.run',
            'imports.create',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // --- 2. Create/Update Roles ---
        
        // Super Admin: All Access
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Admin: Manage Users, Locations (including delete all), Import/Export, verification
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions([
            'admin.users.manage',
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',
            'locations.delete_all', // Admin can delete all
            'locations.approve',
            'exports.run',
            'imports.create',
        ]);

        // Staff (Renamed from 'user'): Input, Edit, View only
        $oldUserRole = Role::where('name', 'user')->first();
        if ($oldUserRole) {
            $oldUserRole->update(['name' => 'staff']);
            $roleStaff = $oldUserRole;
        } else {
            $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        }
        
        // Staff permissions: No delete all, No user manage
        $roleStaff->syncPermissions([
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.submit',
            'exports.run',
        ]);
    }
}
