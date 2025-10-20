<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $getDimas = \App\Models\User::where('username', '21010042811990003')->first();

        // Pegawai::create([
        //     'user_id' => $getDimas->id,
        //     'opd_id' => 2,
        //     'nama' => 'Dimas Nugroho Putro',
        //     'nik' => '1234567890',
        //     'nip' => '9876543210',
        //     'no_hp' => '08123456789',
        //     'alamat' => 'Jl. Contoh Alamat No. 1',
        //     'tempat_lahir' => 'Kota Contoh',
        //     'tanggal_lahir' => '1990-01-01',
        //     'jenis_kelamin' => 'laki'
        // ]);
    }
}
