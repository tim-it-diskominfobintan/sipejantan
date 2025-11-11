<?php

namespace App\Exports;

use App\Models\StockOpname;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RusakExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $data = StockOpname::with(['detailbarang'])->where('jenis_opname', 'rusak')->get();
        $tanggalAkhir = null;
        return $data->map(function ($item) {
            return [
                'Detail Barang'        => $item->detailbarang->barang->nama_barang . ' (' . $item->detailbarang->kode_barang . ')',
                'Tanggal Opname'           => $item->tanggal_opname ? Carbon::parse($item->tanggal_laporan)->format('d M Y') : '-',
                'Tanggal Akhir Garansi' => Carbon::parse($item->detailbarang->tanggal_mulai_garansi)->addMonths($item->detailbarang->lama_garansi)->format('d M Y'),
                'Jenis Opname'             => 'Rusak',
                'Jumlah Opname'         => $item->jumlah_opname,
                'No Bukti'         => $item->no_bukti,
                'Keterangan'         => $item->keterangan,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Detail Barang',
            'Tanggal Opname',
            'Tanggal Akhir Garansi',
            'Jenis Opname',
            'Jumlah Opname',
            'No Bukti',
            'Keterangan'
        ];
    }
}
