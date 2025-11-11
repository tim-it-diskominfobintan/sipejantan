<?php

namespace App\Exports;

use App\Models\Laporan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = Laporan::with(['teknisi', 'pelapor', 'asset'])->get();

        return $data->map(function ($item) {
            return [
                'No Laporan'        => $item->no_laporan,
                'Pelapor'           => $item->pelapor ? $item->pelapor->nama . ' (' . $item->pelapor->nik . ')' : '-',
                'Asset'             => $item->asset->nama_asset ?? '-',
                'Deskripsi'         => $item->deskripsi_laporan,
                'Status Laporan'    => $item->status_laporan,
                'Tanggal Laporan' => $item->tanggal_laporan
                    ? Carbon::parse($item->tanggal_laporan)->format('d M Y')
                    : '-',
                'Teknisi'           => $item->teknisi->nama_teknisi ?? '-',
                'Keterangan'        => $item->ket_tolak,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Laporan',
            'Pelapor',
            'Asset',
            'Deskripsi',
            'Status Laporan',
            'Tanggal Laporan',
            'Teknisi',
            'Keterangan'
        ];
    }
}
