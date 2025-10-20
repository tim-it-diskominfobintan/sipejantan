<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Kecamatan;
use App\Models\JenisAsset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GrafikController extends Controller
{
    public function index(Request $request, $id)
    {

        // Total semua PJU
        $totalPju = Asset::where('jenis_asset_id', $id)->count();

        // Data kondisi
        $kondisiCounts = Asset::selectRaw("
        SUM(CASE WHEN kondisi = 'baik' THEN 1 ELSE 0 END) as baik,
        SUM(CASE WHEN kondisi = 'rusak_ringan' THEN 1 ELSE 0 END) as rusak_ringan,
        SUM(CASE WHEN kondisi = 'rusak_berat' THEN 1 ELSE 0 END) as rusak_berat
        ")->where('jenis_asset_id', $id)->first();

        // Data per kecamatan (untuk bar chart)
        $kecamatanData = Kecamatan::withCount([
            'assets as baik' => function ($q) use ($id) {
                $q->where('kondisi', 'baik')->where('jenis_asset_id', $id);
            },
            'assets as rusak_ringan' => function ($q) use ($id) {
                $q->where('kondisi', 'rusak_ringan')->where('jenis_asset_id', $id);
            },
            'assets as rusak_berat' => function ($q) use ($id) {
                $q->where('kondisi', 'rusak_berat')->where('jenis_asset_id', $id);
            }
        ])->get();

        $jenisAsset = JenisAsset::findOrFail($id);
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset', 'kondisi')->where('jenis_asset_id', $id)->get();
        $title = 'Grafik Asset ' . $jenisAsset->jenis_asset;
        $jenis_asset = JenisAsset::all();
        return view('grafik.index', compact('assets', 'title', 'jenisAsset', 'jenis_asset', 'totalPju', 'kondisiCounts', 'kecamatanData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
