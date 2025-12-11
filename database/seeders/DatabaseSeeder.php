<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CustomersSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            RoleSeeder::class,
//            PermissionSeeder::class,
//            AdminUserSeeder::class,
//            BrandSeeder::class,
//            CategorySeeder::class,
//            ProductSeeder::class,
//            ProductImageSeeder::class,
        ]);
    }
}
