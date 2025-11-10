<?php

namespace App\Imports;

use App\Models\Jalan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PenanggungJawab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JalanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Mencari ID relasi berdasarkan nama
        $kecamatan = Kecamatan::where('id_kecamatan', $row['kecamatan'] ?? '')->first();
        $kelurahan = Kelurahan::where('id_kelurahan', $row['kelurahan_desa'] ?? '')->first();
        $penanggungJawab = PenanggungJawab::where('id_penanggung_jawab', $row['penanggung_jawab'] ?? '')->first();

        return new Jalan([
            'nama_jalan' => $row['nama_jalan'] ?? null,
            'panjang_jalan' => $row['panjang_m'] ?? null,
            'kecamatan_id' => $kecamatan->id_kecamatan ?? null,
            'kelurahan_id' => $kelurahan->id_kelurahan ?? null,
            'penanggung_jawab_id' => $penanggungJawab->id_penanggung_jawab ?? null,
        ]);
    }
}
