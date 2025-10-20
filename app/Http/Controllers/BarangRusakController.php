<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Laporan;
use App\Models\Pelapor;
use App\Models\Teknisi;
use App\Models\Perbaikan;
use App\Models\DokLaporan;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use App\Models\TransPerbaikanBarang;
use Yajra\DataTables\Facades\DataTables;

class BarangRusakController extends Controller
{
    private $logContext = 'master_stok_opname';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpname::whereIn('jenis_opname', ['rusak'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('detail_barang', function ($row) {
                    $classname = "badge badge-light d-block text-start p-2";
                    return '
                        <div class="' . $classname . '">
                            <div class="fw-semibold mb-2">' . $row->detailbarang->barang->nama_barang . '</div>
                            <div class="text-muted">(' . $row->detailbarang->kode_barang . ')</div>
                        </div>
                    ';
                })
                ->addColumn('tanggal', function ($row) {
                    // Tanggal opname
                    $tanggalOpname = !empty($row->tanggal_opname)
                        ? dateTimeIndo($row->tanggal_opname)
                        : '-';
                    $classOpname = "badge badge-outline text-primary";

                    // Default
                    $tanggalAkhir = null;
                    $classAkhir = "badge badge-outline text-secondary";

                    // Hitung tanggal akhir garansi kalau datanya lengkap
                    if (!empty($row->detailbarang->tanggal_mulai_garansi) && !empty($row->detailbarang->lama_garansi)) {
                        $tanggalAkhir = \Carbon\Carbon::parse($row->detailbarang->tanggal_mulai_garansi)
                            ->addMonths($row->detailbarang->lama_garansi);

                        $today = \Carbon\Carbon::today();
                        $classAkhir = $tanggalAkhir->gte($today)
                            ? "badge badge-outline text-green"   // masih berlaku
                            : "badge badge-outline text-red";    // sudah habis
                    }

                    $akhirText = $tanggalAkhir ? $tanggalAkhir->format('d F Y') : '-';

                    return '
                        <div>
                            <div class="' . $classOpname . ' mb-1">Opname: ' . $tanggalOpname . '</div><br>
                            <div class="' . $classAkhir . '">Akhir Garansi: ' . $akhirText . '</div>
                        </div>
                    ';
                })

                ->addColumn('jumlah_opname', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->jumlah_opname . '
                        </div>
                    ';
                })
                ->addColumn('no_bukti', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->no_bukti . '
                        </div>
                    ';
                })
                ->addColumn('keterangan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->keterangan . '
                        </div>
                    ';
                })
                ->addColumn('created_by', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->created_by . '
                        </div>
                    ';
                })
                ->addColumn('jenis', function ($row) {
                    if ($row->jenis_opname == 'masuk') {
                        $classname = "badge badge-outline text-blue";
                        $jenis = 'Masuk';
                    } else if ($row->jenis_opname == 'keluar') {
                        $classname = "badge badge-outline text-yellow";
                        $jenis = 'Keluar';
                    } else {
                        $classname = "badge badge-outline text-red";
                        $jenis = 'Rusak';
                    }

                    return '
                        <div class="' . $classname . '">
                            ' . $jenis . '
                        </div>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $str = $row->no_bukti;
                    $nomor = explode('/', $str)[1]; // hasil: 35

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .= '<a href="' . url("admin/detail_barang_status/$nomor") . '" class="btn-detail btn btn-danger-light shadow-none" data-id="' . $row->id_opname . '"><i class="bi bi-eye"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['barang', 'tanggal', 'jenis', 'actions', 'detail_barang', 'jumlah_opname', 'no_bukti', 'keterangan', 'created_by'])
                ->make(true);
        }

        $data['title'] = "Detail Stok Opname Barang";
        $data['subtitle'] = "Manajemen Barang";
        $data['barang'] = Barang::all();


        return view('admin.stock_opname.index', $data);
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

    public function detail($id)
    {
        $data['title'] = "Detail Stok Opname Barang";
        $data['subtitle'] = "Manajemen Barang";

        $perbaikan = Perbaikan::findorFail($id);
        $lapor = Laporan::where('id_laporan', $perbaikan->laporan_id)->first();
        $data['barang'] = Barang::all();
        $data['laporan'] = Laporan::where('id_laporan', $perbaikan->laporan_id)->first();
        $data['pelapor'] = Pelapor::where('id_pelapor', $lapor->pelapor_id)->first();
        $data['perbaikan'] =  $perbaikan;
        $data['trans_barang_perbaikan'] = TransPerbaikanBarang::with('detailbarang.barang')->where('perbaikan_id', $id)->get();
        $data['teknisi'] = Teknisi::all();
        $data['barang_digunakan'] = StockOpname::with('detailbarang.barang')
            ->where('no_bukti', 'like', "%/$id%")
            ->where('jenis_opname', 'keluar')
            ->get();

        $data['barang_rusak'] = StockOpname::with('detailbarang.barang')
            ->where('no_bukti', 'like', "%/$id%")
            ->where('jenis_opname', 'rusak')
            ->get();
        $data['teknisi'] = Teknisi::all();
        $data['doklaporan'] = DokLaporan::where('laporan_id', $perbaikan->laporan_id)->get();

        return view('admin.stock_opname.detail', $data);
    }
}
