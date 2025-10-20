<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Barang;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\DetailBarang;
use App\Models\StockOpname;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class BarangController extends Controller
{
    private $logContext = 'master_barang';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_barang', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->nama_barang . '
                        </div>
                    ';
                })
                ->addColumn('satuan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->satuan . '
                        </div>
                    ';
                })
                ->addColumn('stok_awal', function ($row) {
                    $sA = stokAwalBarangId($row->id_barang);
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $sA . ' ' . $row->satuan . '
                        </div>
                    ';
                })
                ->addColumn('stok_sekarang', function ($row) {
                    $sK = stokSekrangBarangId($row->id_barang);

                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $sK . ' ' . $row->satuan . '
                        </div>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $btn = '<div class="btn-group shadow-none">';

                    $btn .= '<a href="' . url('admin/detail_barang/' . $row->id_barang) . '" class="btn-list-detail btn btn-light-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_barang . '"><i class="bi bi-card-checklist"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-list-opname btn btn-light-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_barang . '"><i class="bi bi-list-check"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-opname btn btn-success-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_barang . '"><i class="bi bi-boxes"></i></a>';

                    $btn .=  '<a href="javascript:void(0)" class="btn-update btn btn-warning-light shadow-none" data-detail="' . $detail . '" data-id="' . $row->id_barang . '"><i class="bi bi-pen"></i></a>';

                    $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_barang . '"><i class="bi bi-trash"></i></a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['actions', 'nama_barang', 'stok_awal', 'stok_sekarang', 'satuan'])
                ->make(true);
        }

        $data['title'] = "Barang";
        $data['subtitle'] = "Manajemen Barang";

        return view('admin.barang.index', $data);
    }


    public function create() {}

    public function store(Request $request)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'nama_barang' => 'required|unique:m_barang,nama_barang',
                'satuan' => 'required',
            ]);

            DB::beginTransaction();
            $data = Barang::create($validated);
            Helper::createLog($data, 'created', $this->logContext, 'membuat Data Barang: ' . $data->nama_barang, [], $data);
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

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'nama_barang' => 'required',
                'satuan' => 'required',
            ]);

            DB::beginTransaction();
            $data = Barang::findOrFail($id);
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext, 'mengubah data barang: ' . $newData->nama_barang, $oldData, $newData);
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

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);
        // delete detail barang dulu dan opname yang berhubungan dengan barang ini
        try {
            $data = Barang::findOrFail($id);
            DB::beginTransaction();

            //delete stok opname yang berhubungan dengan barang ini yang hanya ada detail barang id
            StockOpname::whereIn('detail_barang_id', $data->detailBarang->pluck('id_detail_barang'))->delete();

            // delete detail barang yang ada id barang beserta file gambarnya
            foreach ($data->detailbarang as $detail) {
                if ($detail->foto_detail_barang && Storage::exists($detail->foto_detail_barang)) {
                    Storage::delete($detail->foto_detail_barang);
                }
                $detail->delete();
            }

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus barang: ' . $oldData->nama_kecamatan, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function listOpname($barangId)
    {
        $data = StockOpname::with('detailbarang.barang')
            ->whereHas('detailbarang', function ($q) use ($barangId) {
                $q->where('barang_id', $barangId);
            })
            ->orderBy('detail_barang_id', 'asc')
            ->get();

        $stok = 0; // default stok awal (kalau memang stok awal = opname pertama)

        foreach ($data as $item) {
            if ($item->jenis_opname === 'masuk') {
                $stok += $item->jumlah_opname;
            } else {
                $stok -= $item->jumlah_opname;
            }
            $item->stok_akhir = $stok;
        }

        return response()->json($data);
    }

    public function list_detail($id)
    {
        $data = DetailBarang::with('barang')
            ->where('barang_id', $id)
            ->get();

        return response()->json($data);
    }
}
