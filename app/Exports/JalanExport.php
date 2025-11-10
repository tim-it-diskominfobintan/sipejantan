<?php

namespace App\Exports;

use App\Models\Jalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JalanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = Jalan::with(['kecamatan', 'kelurahan', 'penanggung_jawab'])->get();

        return $data->map(function ($item) {
            return [
                'ID Jalan'          => $item->id_jalan,
                'Nama Jalan'        => $item->nama_jalan,
                'Panjang (m)'       => $item->panjang_jalan,
                'Kecamatan'         => $item->kecamatan->nama_kecamatan ?? '-',
                'Kelurahan/Desa'    => $item->kelurahan->nama_kelurahan ?? '-',
                'Penanggung Jawab'  => $item->penanggung_jawab->nama_penanggung_jawab ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Jalan',
            'Nama Jalan',
            'Panjang (m)',
            'Kecamatan',
            'Kelurahan/Desa',
            'Penanggung Jawab',
        ];
    }
}
