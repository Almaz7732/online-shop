<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'example@example.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        // Assign admin role to user
        $adminRole = Role::findByName('admin');
        $admin->assignRole($adminRole);
    }
}
