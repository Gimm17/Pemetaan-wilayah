<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@peta.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'), // Change this in production
            ]
        );
        
        $admin->assignRole('super-admin');

        // 2. Create Regular User (Example)
        $user = User::firstOrCreate(
            ['email' => 'user@peta.local'],
            [
                'name' => 'Staf Desa',
                'password' => Hash::make('password123'),
            ]
        );
        
        $user->assignRole('user');
    }
}
