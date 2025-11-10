<?php

namespace App\Exports;

use App\Models\Jalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JalanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Pilih kolom sesuai tabel kamu
        return Jalan::select('id_jalan', 'nama_jalan', 'panjang_jalan', 'kecamatan_id', 'kelurahan_id', 'penanggung_jawab_id')->get();
    }

    public function headings(): array
    {
        return ['ID Jalan', 'Nama Jalan', 'Panjang (m)', 'Kecamatan', 'Kelurahan', 'Penanggung Jawab'];
    }
}
