<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\Perbaikan;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Models\TransPerbaikanBarang;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class StokOpnameController extends Controller
{
    private $logContext = 'master_stok_opname';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpname::whereIn('jenis_opname', ['masuk', 'keluar'])->latest()->get();
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
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . dateTimeIndo($row->tanggal_opname) . '
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
                    } else {
                        $classname = "badge badge-outline text-yellow";
                        $jenis = 'Keluar';
                    }

                    return '
                        <div class="' . $classname . '">
                            ' . $jenis . '
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
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));
                    $str = $row->no_bukti;

                    // Pastikan ada tanda '/'
                    $nomor = null;
                    if (strpos($str, '/') !== false) {
                        $parts = explode('/', $str);
                        $nomor = $parts[1] ?? null; // aman jika tidak ada index [1]
                    }

                    $btn = '<div class="btn-group shadow-none">';

                    if ($nomor) {
                        $btn .= '<a href="' . url("admin/detail_barang_status/$nomor") . '" class="btn-detail btn btn-danger-light shadow-none" data-id="' . $row->id_opname . '"><i class="bi bi-eye"></i></a>';
                    } else {
                        $btn .= '<button class="btn btn-secondary-light shadow-none" disabled><i class="bi bi-eye-slash"></i></button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['barang', 'tanggal', 'jenis', 'actions', 'detail_barang', 'jumlah_opname', 'no_bukti', 'keterangan', 'created_by'])
                ->make(true);
        }

        $data['title'] = "Stok Opname";
        $data['subtitle'] = "Manajemen Stok Opname";
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
        dd('1');
    }

    public function store(Request $request)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'detail_barang_id'      => 'required|exists:detail_barang,id_detail_barang',
                'tanggal_opname' => 'required|date',
                'jenis_opname'   => 'required|in:masuk,keluar,rusak',
                'jumlah_opname'  => 'required|numeric|min:1',
                'no_bukti'       => 'required|string',
                'keterangan'     => 'required|string',
            ]);

            DB::beginTransaction();

            if ($request->jenis_opname === 'keluar' || $request->jenis_opname === 'rusak') {
                $perbaikan = Perbaikan::with('laporan')->find($request->no_bukti);
                $no_bukti = $perbaikan->laporan->no_laporan . '/' . $perbaikan->id_perbaikan;
            }
            $validated['no_bukti'] = $request->jenis_opname === 'masuk' ? $request->no_bukti : $no_bukti;

            $user = Auth::user();
            $validated['created_by'] = $user->username;
            $data = StockOpname::create($validated);

            Helper::createLog(
                $data,
                'created',
                $this->logContext,
                'membuat stock opname: ' . $data->barang_id,
                [],
                $data
            );

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

    public function update(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $validated = $request->validate([
                'barang_id'      => 'required|exists:m_barang,id_barang',
                'tanggal_opname' => 'required|date',
                'jenis_opname'   => 'required|in:masuk,keluar',
                'jumlah_opname'  => 'required|numeric|min:1',
                'no_bukti'       => 'required|string',
                'keterangan'     => 'required|string',
            ]);

            $opnameLama = StockOpname::findOrFail($id);
            $barang     = Barang::findOrFail($request->barang_id);

            DB::beginTransaction();

            // Kembalikan stok ke posisi sebelum ada opname lama
            if ($opnameLama->jenis_opname === 'masuk') {
                $barang->decrement('stok', $opnameLama->jumlah_opname);
            } else {
                $barang->increment('stok', $opnameLama->jumlah_opname);
            }

            // Terapkan opname baru
            if ($request->jenis_opname === 'masuk') {
                $barang->increment('stok', $request->jumlah_opname);
            } else {
                if ($request->jumlah_opname > $barang->stok) {
                    return JsonResponseHelper::info('Jumlah melebihi stok tersedia', JsonResponseHelper::$FAILED_INFO . " " . 'Jumlah melebihi stok tersedia ' . $barang->stok);
                }
                $barang->decrement('stok', $request->jumlah_opname);
            }

            // Update data opname
            $opnameLama->update($validated);

            DB::commit();

            return JsonResponseHelper::success($opnameLama, 'Data berhasil diperbarui', 200);
        } catch (ValidationException $error) {
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            return JsonResponseHelper::error($e->getMessage(), 'Gagal update data');
        }
    }

    public function destroy(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            $data = StockOpname::findOrFail($id);
            DB::beginTransaction();

            $barang = Barang::findOrFail($data->barang_id);
            if ($barang->jenis_opname === 'masuk') {
                $barang->decrement('stok', $data->jumlah_opname);
            } else {
                $barang->increment('stok', $data->jumlah_opname);
            }

            $oldData = clone $data;
            $data->delete();
            Helper::createLog($oldData, 'deleted', $this->logContext, 'menghapus kecamatan: ' . $oldData->nama_kecamatan, $oldData, []);

            DB::commit();
            return JsonResponseHelper::success(['id' => $oldData->id], JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_STORE . " " . $e->getMessage());
        }
    }

    public function trans_barang_perbaikan()
    {
        return Perbaikan::with('laporan')->latest()->get();
    }
}
