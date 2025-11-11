<?php

namespace App\Exports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class KelurahanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = Kelurahan::with(['kecamatan'])->get();

        return $data->map(function ($item) {
            return [
                'ID Kelurahan'          => $item->id_kelurahan,
                'Kode Kelurahan'        => $item->kode_kelurahan,
                'Nama Kelurahan'       => $item->nama_kelurahan,
                'Nama Kecamatan'       => $item->kecamatan->nama_kecamatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Kelurahan',
            'Kode Kelurahan',
            'Nama Kelurahan',
            'Nama Kecamatan'
        ];
    }
}
