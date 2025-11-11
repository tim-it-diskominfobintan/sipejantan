<?php

namespace App\Exports;

use App\Models\JenisAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JenisAssetExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = JenisAsset::get();

        return $data->map(function ($item) {
            return [
                'ID Jenis Asset'          => $item->id_jenis_asset,
                'Nama Jenis Asset'        => $item->jenis_asset,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Jenis Asset',
            'Nama Jenis Asset',
        ];
    }
}
