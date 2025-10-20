<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Jalan;
use App\Models\Laporan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisAsset;
use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use App\Http\Controllers\Controller;
use App\Models\DokumenAsset;
use Yajra\DataTables\Facades\DataTables;

class ListAssettController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Asset::where('jenis_asset_id', $id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis_asset', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->jenis_asset->jenis_asset . '
                        </div>
                    ';
                })
                ->addColumn('nama_penanggung_jawab', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->penanggung_jawab->nama_penanggung_jawab . '
                        </div>
                    ';
                })
                ->addColumn('nama_jalan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->jalan->nama_jalan . '
                        </div>
                    ';
                })
                ->addColumn('nama_kecamatan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kecamatan->nama_kecamatan . '
                        </div>
                    ';
                })
                ->addColumn('nama_kelurahan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->kelurahan->nama_kelurahan . '
                        </div>
                    ';
                })
                ->addColumn('foto', function ($row) {
                    $row = '
                            <div class="d-flex align-items-center badge badge-light">
                                <div class="flex-shrink-0">
                                    <img src="' . ($row->foto_asset ? asset('storage/' . $row->foto_asset) : asset('assets/global/img/kardus.png')) . '"  
                                        class="rounded-pill" 
                                        alt="photo-profile" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                            </div>
                        ';
                    return $row;
                })
                ->addColumn('kode', function ($row) {
                    $row = '
                    <div class="kode-barcode-container badge badge-light">
                        <span class="kode-text me-2">' . $row->kode_asset . '</span>
                    </div>';
                    return $row;
                })
                ->addColumn('nama_asset', function ($row) {
                    $row = '
                    <div class="badge badge-light">
                        <span class="kode-text me-2">' . $row->nama_asset . '<br><br>
                        <small>' . $row->kode_asset . '</small></span>
                    </div>';
                    return $row;
                })
                ->addColumn('kondisi', function ($row) {
                    if ($row->kondisi == 'baik') {
                        $classname = "badge badge-success";
                        $text = "Baik";
                    } elseif ($row->kondisi == 'rusak_ringan') {
                        $classname = "badge badge-warning";
                        $text = "Rusak Ringan";
                    } elseif ($row->kondisi == 'rusak_berat') {
                        $classname = "badge badge-danger";
                        $text = "Rusak Berat";
                    }
                    return '
                        <div class="' . $classname . '">
                            ' . $text . '
                        </div>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $existdilaporan = Laporan::where('asset_id', $row->id_asset)->exists();
                    $btn = '<div class="btn-group shadow-none">';
                    $btn .= '<a href="' . url('jenisAsset/asset/' . $row->id_asset . '/detail') . '" target="_blank" class="btn-detail btn btn-info-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_asset . '"><i class="bi bi-eye"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['jenis_asset', 'nama_penanggung_jawab', 'nama_jalan', 'actions', 'kode', 'foto', 'nama_asset', 'kondisi', 'nama_kecamatan', 'nama_kelurahan'])
                ->make(true);
        }

        $jenisAsset = JenisAsset::findOrFail($id);
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan', 'latestLaporan'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset', 'kondisi')->where('jenis_asset_id', $id)->get();
        $title = 'Daftar Asset ' . $jenisAsset->jenis_asset;
        $jenis_asset = JenisAsset::all();
        return view('guest.jenisAsset', compact('assets', 'title', 'jenisAsset', 'jenis_asset'));
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

    public function detail($id)
    {

        $data['title'] = "Manajemen Asset";
        $data['subtitle'] = "Detail Asset";
        $data['jalan'] = Jalan::all();
        $data['penanggung_jawab'] = PenanggungJawab::all();
        $data['jenis_asset'] = JenisAsset::all();
        $data['asset'] = Asset::findOrFail($id);
        $data['kelurahan'] = Kelurahan::all();
        $data['kecamatan'] = Kecamatan::all();
        $data['dokumen_asset'] = DokumenAsset::where('asset_id', $id)->get();
        return view('guest.assetDetail', $data);
    }
}
