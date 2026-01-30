<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PaluGisSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',
            'locations.submit',
            'locations.approve',
            'imports.create',
            'exports.run',
            'admin.users.manage',
        ];

        foreach ($perms as $p) {
            Permission::findOrCreate($p);
        }

        $admin = Role::findOrCreate('admin');
        $operator = Role::findOrCreate('operator');
        $viewer = Role::findOrCreate('viewer');

        $admin->syncPermissions($perms);
        $operator->syncPermissions([
            'locations.view','locations.create','locations.edit','locations.submit',
            'imports.create','exports.run'
        ]);
        $viewer->syncPermissions(['locations.view']);

        $u = User::firstOrCreate(
            ['email' => 'admin@palu.local'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        if (!$u->hasRole('admin')) {
            $u->assignRole('admin');
        }
    }
}
