<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permission
        $permission = Permission::create(['name' => 'admin-setting']);

        // Assign permission to admin role
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo($permission);
    }
}