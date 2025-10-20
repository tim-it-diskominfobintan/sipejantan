<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AuthProviderSeeder::class);
         $this->call(RoleSeeder::class);
         $this->call(OpdSeeder::class);
         $this->call(UserSeeder::class);
         $this->call(PegawaiSeeder::class);
         $this->call(AdminSeeder::class);
    }
}
