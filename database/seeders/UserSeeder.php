<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\ProfilAdmin;
use App\Models\ProfilPegawai;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'status' => 'active',
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'last_device' => 'desktop',
        ]);
        $user->assignRole('admin');
        ProfilAdmin::create([
            'user_id' => $user->id,
            'posisi' => 'Admin Utama'
        ]);

        $user = User::create([
            'username' => 'admin_bkspdm',
            'name' => 'Admin BKPSDM',
            'email' => 'admin_bkspdm@example.com',
            'password' => bcrypt('admin123'),
            'status' => 'active',
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'last_device' => 'desktop',
        ]);
        $user->assignRole('opd');
        $user->opd()->attach(5);
        ProfilAdmin::create([
            'user_id' => $user->id,
            'posisi' => 'Admin Bkpsdm'
        ]);
        
        if (app()->environment() == 'local') {
            $user = User::create([
                'username' => 'user1',
                'name' => 'User Satu',
                'email' => 'user1@example.com',
                'password' => bcrypt('password123'),
                'status' => 'active',
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
                'last_device' => 'desktop',
            ]);
            $user->assignRole('user');
            $user->opd()->attach(2);
            ProfilPegawai::create([
                'user_id' => $user->id,
                'nik' => '123',
                'nip' => '123',
                'no_hp' => '08123456789',
                'alamat' => 'Jl. Contoh Alamat No. 1',
                'tempat_lahir' => 'Kota Contoh',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'laki'
            ]);

            $user = User::create([
                'username' => '21010042811990003',
                'name' => 'Dimas Nugroho Putro',
                'email' => 'dimasugroho673@gmail.com',
                'password' => bcrypt('password123'),
                'status' => 'active',
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
                'last_device' => 'desktop',
            ]);
            $user->assignRole('user');
            $user->opd()->attach(2);
            ProfilPegawai::create([
                'user_id' => $user->id,
                'nik' => '1234567890',
                'nip' => '9876543210',
                'no_hp' => '08123456789',
                'alamat' => 'Jl. Contoh Alamat No. 1',
                'tempat_lahir' => 'Kota Contoh',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'laki'
            ]);
        }
    }
}
