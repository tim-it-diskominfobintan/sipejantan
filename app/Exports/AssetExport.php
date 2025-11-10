<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = Asset::with(['kecamatan', 'kelurahan', 'penanggung_jawab', 'jenis_asset', 'jalan'])->get();

        return $data->map(function ($item) {
            return [
                'Kode Asset'        => $item->kode_asset,
                'Nama Asset'        => $item->nama_asset,
                'Jenis Asset'       => $item->jenis_asset->jenis_asset ?? '-',
                'Nama Jalan'        => $item->jalan->nama_jalan ?? '-',
                'Kecamatan'         => $item->kecamatan->nama_kecamatan ?? '-',
                'Kelurahan/Desa'    => $item->kelurahan->nama_kelurahan ?? '-',
                'Penanggung Jawab'  => $item->penanggung_jawab->nama_penanggung_jawab ?? '-',
                'Koordinat'         => $item->koordinat,
                'Kondisi'           => $item->kondisi,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Asset',
            'Nama Asset',
            'Jenis Asset',
            'Nama Jalan',
            'Kecamatan',
            'Kelurahan/Desa',
            'Penanggung Jawab',
            'Koordinat',
            'Kondisi'
        ];
    }
}
