<?php

namespace App\Exports;

use App\Models\Kecamatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KecamatanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = Kecamatan::get();

        return $data->map(function ($item) {
            return [
                'ID Kecamatan'          => $item->id_kecamatan,
                'Kode Kecamatan'        => $item->kode_kecamatan,
                'Nama Kecamatan'       => $item->nama_kecamatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Kecamatan',
            'Kode Kecamatan',
            'Nama Kecamatan',
        ];
    }
}
