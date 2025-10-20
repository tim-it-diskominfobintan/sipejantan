<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\StockOpname;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class DetailBarangController extends Controller
{
    private $logContext = 'detail_barang';

    public function index(Request $request, $id)
    {
        $id = $request->id;
        if ($request->ajax()) {
            $data = DetailBarang::where('barang_id', $id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_akhir_garansi', function ($row) {
                    if ($row->tanggal_mulai_garansi && $row->lama_garansi) {
                        $tanggalAkhir = \Carbon\Carbon::parse($row->tanggal_mulai_garansi)
                            ->addMonths($row->lama_garansi);
                        $today = \Carbon\Carbon::today();
                        $classname = $tanggalAkhir->gte($today)
                            ? "badge badge-outline text-green"   // masih berlaku
                            : "badge badge-outline text-red";   // sudah habis

                        return '
                            <div class="' . $classname . '">
                                ' . $tanggalAkhir->format('d F Y') . '
                            </div>
                        ';
                    }

                    return '<div class="badge badge-outline text-default">-</div>';
                })
                ->addColumn('tanggal_garansi', function ($row) {
                    return '
                            <div class="badge badge-outline text-default">
                                ' . \Carbon\Carbon::parse($row->tanggal_mulai_garansi)->format('d F Y') . '
                            </div>
                        ';
                })
                ->addColumn('tanggal_masuk', function ($row) {
                    return '
                            <div class="badge badge-outline text-default">
                                ' . \Carbon\Carbon::parse($row->tanggal_masuk)->format('d F Y') . '
                            </div>
                        ';
                })
                ->addColumn('nama_barang', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->barang->nama_barang . '
                        </div>
                    ';
                })
                ->addColumn('stok_awal', function ($row) {
                    // menggunakan helper stok awal
                    $stok = CekStokAwal($row->id_detail_barang);

                    $classname = $stok > 0 ? "badge badge-light" : "badge badge-danger-light";

                    return '
                        <div class="' . $classname . '">
                            ' . $stok . '
                        </div>
                    ';
                })
                ->addColumn('stok_sekarang', function ($row) {
                    // menggunakan helper stok awal
                    $stok = CekStok($row->id_detail_barang);

                    $classname = $stok > 0 ? "badge badge-light" : "badge badge-danger-light";

                    return '
                        <div class="' . $classname . '">
                            ' . $stok . '
                        </div>
                    ';
                })
                ->addColumn('foto', function ($row) {
                    $row = '
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="' . ($row->foto_detail_barang ? asset('storage/' . $row->foto_detail_barang) : asset('assets/global/img/kardus.png')) . '"  
                                        class="rounded-pill" 
                                        alt="photo-profile" 
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                            </div>
                        ';
                    return $row;
                })
                ->addColumn('kode', function ($row) {
                    $classname = "badge badge-light";
                    $row = '
                    <div class="kode-barcode-container ' . $classname . '">
                        <span class="kode-text me-2">' . $row->kode_barang . '</span>
                        <a href="javascript:void(0)" class="btn-barcode btn btn-primary-light shadow-none" data-bs-toggle="modal" data-bs-target="#barcodeModal" data-kode=' . $row->kode_barang . '>
                            <i class="bi bi-upc-scan"></i>
                        </a>
                    </div>';
                    return $row;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $stokAwal = StockOpname::where('detail_barang_id', $row->id_detail_barang)
                        ->where('jenis_opname', 'masuk')->orderBy('tanggal_opname', 'asc')->orderBy('id_opname', 'asc')->first();
                    $stokAwalJumlah = $stokAwal ? $stokAwal->jumlah_opname : 0;

                    $stokSekarang = CekStok($row->id_detail_barang);
                    $btn = '<div class="btn-group shadow-none">';

                    $btn .= '<a href="javascript:void(0)" class="btn-list-opname btn btn-light-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_detail_barang . '" data-idbarang="' . $row->barang_id . '"><i class="bi bi-list-check"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-opname btn btn-success-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_detail_barang . '" ><i class="bi bi-boxes"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-detail btn  btn-primary-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_detail_barang . '" data-stok_awal="' . $stokAwalJumlah . '" data-sekarang="' . $stokSekarang . '"><i class="bi bi-eye"></i></a>';

                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_detail_barang . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_detail_barang . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['tanggal_akhir_garansi', 'foto', 'kode', 'actions', 'nama_barang', 'tanggal_garansi', 'tanggal_masuk', 'stok_sekarang', 'stok_awal'])
                ->make(true);
        }

        $data['title'] = "Detail Barang";
        $data['subtitle'] = "Manajemen Detail Barang";

        return view('admin.detail_barang.index', $data);
    }

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
    public function store(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'kode_barang' => 'required|unique:detail_barang,kode_barang',
                'tanggal_mulai_garansi' => 'required',
                'lama_garansi' => 'required',
                'tanggal_masuk' => 'required',
                'keterangan_barang' => 'nullable',
                'foto_detail_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('foto_detail_barang')) {
                $file = $request->file('foto_detail_barang');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('foto_detail_barang', $fileName, 'public');
                $validated['foto_detail_barang'] = $filePath;
            } else {
                $validated['foto_detail_barang'] = null;
            }
            $validated['barang_id'] = $id;
            DB::beginTransaction();
            $data = DetailBarang::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat Data Detail Barang: ' . $request->kode_barang, [], $data);
            DB::commit();

            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
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
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'kode_barang' => 'required',
                'tanggal_mulai_garansi' => 'required',
                'lama_garansi' => 'required',
                'tanggal_masuk' => 'required',
                'keterangan_barang' => 'nullable',
                'foto_detail_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::beginTransaction();
            $data = DetailBarang::findOrFail($id);
            if ($request->hasFile('foto_detail_barang')) {
                $file = $request->file('foto_detail_barang');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('foto_detail_barang', $fileName, 'public');
                $validated['foto_detail_barang'] = $filePath;
            }
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah data detail barang: ' . $request->kode_barang, $oldData, $newData);
            DB::commit();
            return JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE, 201);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete semua opname yang berhubungan dengan detail barang ini
        try {
            DB::beginTransaction();
            $data = DetailBarang::findOrFail($id);

            if ($data->foto_detail_barang && Storage::exists($data->foto_detail_barang)) {
                Storage::delete($data->foto_detail_barang);
            }

            $dataOpname = StockOpname::where('detail_barang_id', $id)->delete();
            $oldData = clone $data;
            $data->delete();
            Helper::createLog($data, 'deleted',  $this->logContext, 'menghapus data detail barang: ' . $data->kode_barang, $oldData, []);
            DB::commit();
            return JsonResponseHelper::success([], JsonResponseHelper::$SUCCESS_DELETE);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_DELETE . " " . $e->getMessage());
        }
    }

    public function listOpname($DetailbarangId)
    {
        $data = StockOpname::with('detailbarang.barang')->where('detail_barang_id', $DetailbarangId)
            ->orderBy('id_opname', 'asc')
            ->get();

        // $detailbarang = DetailBarang::find($DetailbarangId);
        // $stok = $detailbarang->stok_awal;
        // foreach ($data as $item) {
        //     if ($item->jenis_opname === 'masuk') {
        //         $stok += $item->jumlah_opname;
        //     } else {
        //         $stok -= $item->jumlah_opname;
        //     }
        //     $item->stok_akhir = $stok; // tambahkan property stok akhir
        // }

        return response()->json($data);
    }
}
