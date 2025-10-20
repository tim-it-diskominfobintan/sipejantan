<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisAsset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DokLaporan;
use App\Models\Laporan;

class TiketController extends Controller
{

    public function index()
    {
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan', 'dokumen'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset')->get();

        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $title = 'Pengaduan Aset Dinas Perhubungan Kabupaten Bintan';
        $jenis_asset = JenisAsset::all();
        $laporan = Laporan::latest()->paginate(5);
        return view('guest.tiket', compact('assets', 'kecamatan', 'kelurahan', 'title', 'jenis_asset', 'laporan'));
    }


    public function tiket(Request $request)
    {

        // validasi ringan
        $request->validate([
            'no_tiket' => ['nullable', 'regex:/^[A-Za-z0-9\-]+$/', 'max:50'],
        ]);

        $no_tiket = $request->query('no_tiket');

        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan', 'dokumen'])
            ->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset')
            ->get();

        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $title = 'Pengaduan Aset Dinas Perhubungan Kabupaten Bintan';
        $jenis_asset = JenisAsset::all();

        $laporanQuery = Laporan::latest()->with(['pelapor', 'asset', 'dokumen']); // eager load relasi kalau perlu

        if ($request->filled('no_tiket')) {
            $laporanQuery->where('no_laporan', 'like', "%{$no_tiket}%");
        }

        // withQueryString() supaya pagination mempertahankan ?no_tiket=...
        $laporan = $laporanQuery->paginate(5)->withQueryString();

        return view('guest.tiket', compact('assets', 'kecamatan', 'kelurahan', 'title', 'jenis_asset', 'laporan'));
    }
}
