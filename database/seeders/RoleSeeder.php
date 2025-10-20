<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Hak akses tertinggi, milik owner aplikasi atau tim development'
        ]);
        Role::create([
            'name' => 'opd',
            'description' => 'Admin OPD'
        ]);
        Role::create([
            'name' => 'user',
            'description' => 'Pegawai maupun user pada umumnya'
        ]);

        // Jika dirasa butuh role spesifik seperti 'asn', atau 'nonasn'. silahkan tambahkan aja
    }
}
