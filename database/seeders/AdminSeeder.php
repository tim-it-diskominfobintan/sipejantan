<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $getSuperadmin = \App\Models\User::where('username', 'superadmin')->first();

        // Admin::create([
        //     'nama' => 'Superadmin',
        //     'user_id' => $getSuperadmin->id,
        //     'opd_id' => null,
        // ]);


        // $getAdmin = \App\Models\User::where('username', 'admin')->first();
                
        // Admin::create([
        //     'nama' => 'Administrator',
        //     'user_id' => $getAdmin->id,
        //     'opd_id' => null,
        // ]);
    }
}
