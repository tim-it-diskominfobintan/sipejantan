<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\Laporan;
use App\Models\Pelapor;
use App\Models\Teknisi;
use App\Models\Perbaikan;
use App\Models\DokLaporan;
use App\Models\StockOpname;
use App\Models\DetailBarang;
use App\Models\DokPerbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use App\Models\TransPerbaikanBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

class PerbaikanController extends Controller
{
    private $logContext = 'perbaikan';
    private $logContext2 = 'dok_perbaikan';
    private $logContext3 = 'barang';
    private $logContext4 = 'stockOpname';
    private $logContext5 = 'TransPerbaikanBarang';

    public function index(Request $request, $id)
    {
        $laporan = Laporan::where('pelapor_id', $id)->first();

        if ($request->ajax()) {
            $data = Perbaikan::where('laporan_id', $laporan->id_laporan)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('no_laporan', function ($row) {
                    $classname = "badge badge-light";
                    $noLaporan = $row->laporan->no_laporan ?? '-';
                    return '
                        <div class="' . $classname . '">
                            ' . $noLaporan  . '
                        </div>
                    ';
                })
                ->addColumn('jenis_laporan', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->status_perbaikan . '
                        </div>
                    ';
                })
                ->addColumn('progress', function ($row) {
                    $classname = "badge badge-light";
                    return '
                        <div class="' . $classname . '">
                            ' . $row->status_progress . '
                        </div>
                    ';
                })
                ->addColumn('tgl', function ($row) {
                    $classname = "badge badge-light";
                    $tgl = Carbon::parse($row->tanggal_perbaikan)->translatedFormat('d F Y');
                    return '
                        <div class="' . $classname . '">
                            ' . $tgl . '
                        </div>
                    ';
                })
                ->addColumn('teknisi', function ($row) {
                    $classname = "badge badge-light";
                    $teknisi = $row->laporan->teknisi->nama_teknisi ?? '-';
                    return '
                        <div class="' . $classname . '">
                            ' . $teknisi . '
                        </div>
                    ';
                })
                ->addColumn('status_laporan', function ($row) {
                    $status = optional($row->laporan)->status_laporan;

                    if ($status) {
                        switch ($status) {
                            case 'pending':
                                $classname = "badge bg-red text-red-fg";
                                break;
                            case 'diterima':
                                $classname = "badge bg-orange text-orange-fg";
                                break;
                            case 'proses':
                                $classname = "badge bg-blue text-blue-fg";
                                break;
                            default:
                                $classname = "badge bg-green text-green-fg";
                                break;
                        }
                    } else {
                        $classname = "badge bg-secondary"; // default style
                        $status = '-';
                    }

                    return '
                            <div class="' . $classname . '">
                                ' . ucfirst($status) . '
                            </div>
                        ';
                })
                ->addColumn('file', function ($row) {
                    $dokumen = $row->dokPerbaikan ?? []; // pastikan ada relasi di model Perbaikan
                    $btn = '<button type="button" class="btn btn-info btn-sm btn-dok" 
                    data-id="' . $row->id_perbaikan . '" 
                    data-dok=\'' . json_encode($dokumen) . '\'>
                    <i class="bi bi-eye me-1"></i> Lihat
                </button>';
                    return $btn;
                })
                ->addColumn('actions', function ($row) {
                    $detail = htmlspecialchars(json_encode($row));

                    $btn = '<div class="btn-group shadow-none">';
                    $btn .= "<a href='" . url("admin/perbaikan/detail/$row->id_perbaikan") . "' class='btn btn-info-light shadow-none' data-detail='$detail' data-id='$row->id_perbaikan'><i class='bi bi-eye'></i></a>";

                    if (date('Y-m-d') == $row->tanggal_perbaikan) {
                        $btn .= "<a href='" . url("admin/perbaikan/edit/$row->id_perbaikan") . "' class='btn-update btn btn-warning-light shadow-none' data-detail='" . $detail . "' data-id='" . $row->id_perbaikan . "'><i class='bi bi-pen'></i></a>";

                        $btn .= '<a href="javascript:void(0)" class="btn-delete btn btn-danger-light shadow-none" data-id="' . $row->id_perbaikan . '"><i class="bi bi-trash"></i></a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['actions', 'no_laporan', 'jenis_laporan', 'tgl', 'teknisi', 'file', 'status_laporan', 'progress'])
                ->make(true);
        }

        $data['title'] = "Perbaikan";
        $data['subtitle'] = "Manajemen Perbaikan";
        $data['pelapor'] = Pelapor::findorFail($id);
        $data['laporan'] = Laporan::where('pelapor_id', $id)->first();
        $data['doklaporan'] = DokLaporan::where('laporan_id', $laporan->id_laporan)->get();
        $data['teknisi'] = Teknisi::all();

        return view('admin.perbaikan.index', $data);
    }

    public function create($id)
    {
        $laporan = Laporan::where('pelapor_id', $id)->first();
        $data['title'] = "Perbaikan";
        $data['subtitle'] = "Tambah Perbaikan";
        // $data['laporan'] = Laporan::all();
        // $data['barang'] = Barang::all();
        $data['pelapor'] = Pelapor::findorFail($id);
        $data['laporan'] = $laporan;
        $data['teknisi'] = Teknisi::all();
        $data['doklaporan'] = DokLaporan::where('laporan_id', $laporan->id_laporan)->get();
        $data['detailBarang'] = \App\Models\DetailBarang::with('barang')
            ->select(
                'id_detail_barang',
                'barang_id',
                'kode_barang',
                'tanggal_mulai_garansi',
                'lama_garansi',
                DB::raw("
            DATE_FORMAT(
                DATE_ADD(tanggal_mulai_garansi, INTERVAL lama_garansi MONTH),
                '%d %M %Y'
            ) AS tanggal_akhir_garansi
        ")
            )
            ->get();
        return view('admin.perbaikan.form_create', $data);
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
        $user = auth()->user();
        $roleName = $user->roles->pluck('name')->first();

        // Validasi dasar
        $validated = $request->validate([
            'keterangan'           => 'required',
            'status_progress'           => 'required',
            'file_perbaikan'   => 'required',
            'file_perbaikan.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Simpan Perbaikan
            $laporan = Laporan::where('pelapor_id', $id)->first();

            $status_perbaikan = str_starts_with($laporan->no_laporan, 'MAN') ? 'mandiri' : 'umum';

            // simpan data perbaikan
            $perbaikan = new Perbaikan();
            $perbaikan->laporan_id           =  $laporan->id_laporan;
            $perbaikan->tanggal_perbaikan         = date('Y-m-d');
            $perbaikan->keterangan         = $validated['keterangan'];
            $perbaikan->status_perbaikan         = $status_perbaikan;
            $perbaikan->status_progress         = $request->status_progress;
            $perbaikan->created_by              = $user->username ?? 'system';
            $perbaikan->save();
            $logs[] = fn() => Helper::createLog($perbaikan, 'created', $this->logContext, 'menambahkan data perbaikan : ' . $perbaikan->laporan_id);

            // perubahan status laporan 
            $laporan->update(['status_laporan' => $validated['status_progress']]);

            // Simpan Dokumen Perbaikan (file upload)
            if ($request->hasFile('file_perbaikan')) {
                foreach ($request->file('file_perbaikan') as $file) {
                    $path = $file->store('dok_perbaikan', 'public');

                    $dok = new DokPerbaikan();
                    $dok->perbaikan_id   = $perbaikan->id_perbaikan;
                    $dok->file_perbaikan = $path;
                    $dok->tanggal_dokumen = now();
                    $dok->created_by     = $user->username ?? 'system';
                    $dok->save();
                    $logs[] = fn() => Helper::createLog($dok, 'created', $this->logContext2, 'menambahkan data dokumentasi perbaikan : ' . $laporan->id_laporan);
                }
            }

            // Simpan Barang yang digunakan
            if ($request->has('detail_barang_id')) {
                foreach ($request->detail_barang_id as $i => $detailId) {
                    if (!$detailId) continue;
                    $jumlah = $request->jumlah_detail_barang[$i] ?? 1;

                    $detail = DetailBarang::with('barang')->find($detailId);
                    if ($detail && $detail->barang) {
                        $barang = $detail->barang;

                        // Insert ke TransPerbaikanBarang
                        TransPerbaikanBarang::create([
                            'perbaikan_id'          => $perbaikan->id_perbaikan,
                            'detail_barang_id'      => $detailId,
                            'jumlah_barang'  => $jumlah,
                            'created_by'            => $user->username ?? 'system',
                        ]);

                        // Catat ke Stock Opname
                        StockOpname::create([
                            'detail_barang_id' => $detail->id_detail_barang,
                            'tanggal_opname'   => now(),
                            'jenis_opname'     => 'keluar',
                            'jumlah_opname'    => $jumlah,
                            'no_bukti'         => $laporan->no_laporan . '/' . $perbaikan->id_perbaikan,
                            'keterangan'       => 'Barang digunakan untuk perbaikan',
                            'created_by'       => $user->username ?? 'system',
                        ]);
                    }
                }
            }

            if ($request->has('barang_rusak_id')) {
                foreach ($request->barang_rusak_id as $i => $rusakId) {
                    if (!$rusakId) continue;

                    $keterangan = $request->keterangan_rusak[$i] ?? '-';
                    $detailRusak = DetailBarang::find($rusakId);

                    if ($detailRusak) {
                        // Simpan ke StockOpname dengan jenis rusak
                        StockOpname::create([
                            'detail_barang_id' => $detailRusak->id_detail_barang,
                            'tanggal_opname'   => now(),
                            'jenis_opname'     => 'rusak',
                            'jumlah_opname'    => $request->jumlah_barang_rusak[$i] ?? 1,
                            'no_bukti'         => $laporan->no_laporan . '/' . $perbaikan->id_perbaikan,
                            'keterangan'       => $request->keterangan_rusak[$i] ?? 'Barang rusak',
                            'created_by'       => $user->username ?? 'system',
                        ]);
                    }
                }
            }

            Helper::createBatchLogs(...array_filter($logs));
            DB::commit();

            return JsonResponseHelper::success($perbaikan, JsonResponseHelper::$SUCCESS_STORE, 201);
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
        $data['title'] = "Perbaikan";
        $data['subtitle'] = "Detail Perbaikan";
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
        return view('admin.perbaikan.form_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = "Perbaikan";
        $data['subtitle'] = "Edit Perbaikan";
        $perbaikan = Perbaikan::findorFail($id);
        $lapor = Laporan::where('id_laporan', $perbaikan->laporan_id)->first();
        $data['barang'] = Barang::all();
        $data['laporan'] = Laporan::where('id_laporan', $perbaikan->laporan_id)->first();
        $data['pelapor'] = Pelapor::where('id_pelapor', $lapor->pelapor_id)->first();
        $data['perbaikan'] =  $perbaikan;
        $data['trans_barang_perbaikan'] = TransPerbaikanBarang::with('detailbarang.barang')->where('perbaikan_id', $id)->get();
        $data['barang_digunakan'] = StockOpname::with('detailbarang.barang')
            ->where('no_bukti', 'like', "%/$id%")
            ->where('jenis_opname', 'keluar')
            ->get();

        $data['barang_rusak'] = StockOpname::with('detailbarang.barang')
            ->where('no_bukti', 'like', "%/$id%")
            ->where('jenis_opname', 'rusak')
            ->get();

        $data['detailBarang'] = \App\Models\DetailBarang::with('barang')
            ->select(
                'id_detail_barang',
                'barang_id',
                'kode_barang',
                'tanggal_mulai_garansi',
                'lama_garansi',
                DB::raw("
            DATE_FORMAT(
                DATE_ADD(tanggal_mulai_garansi, INTERVAL lama_garansi MONTH),
                '%d %M %Y'
            ) AS tanggal_akhir_garansi
        ")
            )
            ->get();
        $data['doklaporan'] = DokLaporan::where('laporan_id', $perbaikan->laporan_id)->get();
        return view('admin.perbaikan.form_edit', $data);
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
        $validated = $request->validate([
            'keterangan'           => 'required',
            'status_progress'      => 'required',
            'file_perbaikan'       => 'nullable',
            'file_perbaikan.*'     => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        $logs = [];

        try {
            $user = auth()->user();
            $perbaikan = Perbaikan::findOrFail($id);
            $laporan = Laporan::findOrFail($perbaikan->laporan_id);

            // Hapus data lama
            $oldTrans = TransPerbaikanBarang::where('perbaikan_id', $perbaikan->id_perbaikan)->get();
            $oldStock = StockOpname::where('no_bukti', $laporan->no_laporan . '/' . $perbaikan->id_perbaikan)->get();
            $oldDok = DokPerbaikan::where('perbaikan_id', $perbaikan->id_perbaikan)->get();

            // Hapus file lama jika ada file baru
            if ($request->hasFile('file_perbaikan')) {
                foreach ($oldDok as $dok) {
                    if (Storage::disk('public')->exists($dok->file_perbaikan)) {
                        Storage::disk('public')->delete($dok->file_perbaikan);
                    }
                    $dok->delete();
                }
            }

            // Hapus data lama (barang & stok opname)
            TransPerbaikanBarang::where('perbaikan_id', $perbaikan->id_perbaikan)->delete();
            StockOpname::where('no_bukti', $laporan->no_laporan . '/' . $perbaikan->id_perbaikan)->delete();

            // Update data perbaikan utama
            $perbaikan->update([
                'keterangan'       => $validated['keterangan'],
                'status_progress'  => $validated['status_progress'],
                'updated_by'       => $user->username ?? 'system',
            ]);

            // Perbarui status laporan
            $laporan->update(['status_laporan' => $validated['status_progress']]);

            // Simpan dokumen baru (jika ada)
            if ($request->hasFile('file_perbaikan')) {
                foreach ($request->file('file_perbaikan') as $file) {
                    $path = $file->store('dok_perbaikan', 'public');

                    $dok = new DokPerbaikan();
                    $dok->perbaikan_id    = $perbaikan->id_perbaikan;
                    $dok->file_perbaikan  = $path;
                    $dok->tanggal_dokumen = now();
                    $dok->created_by      = $user->username ?? 'system';
                    $dok->save();

                    $logs[] = fn() => Helper::createLog($dok, 'created', $this->logContext2, 'Menambah dokumen perbaikan saat update');
                }
            }

            // Barang yang digunakan (keluar)
            if ($request->has('detail_barang_id')) {
                foreach ($request->detail_barang_id as $i => $detailId) {
                    if (!$detailId) continue;
                    $jumlah = $request->jumlah_detail_barang[$i] ?? 1;

                    $detail = DetailBarang::with('barang')->find($detailId);
                    if ($detail && $detail->barang) {
                        // Simpan transaksi barang
                        TransPerbaikanBarang::create([
                            'perbaikan_id'     => $perbaikan->id_perbaikan,
                            'detail_barang_id' => $detailId,
                            'jumlah_barang'    => $jumlah,
                            'created_by'       => $user->username ?? 'system',
                        ]);

                        // Catat stok opname keluar
                        StockOpname::create([
                            'detail_barang_id' => $detail->id_detail_barang,
                            'tanggal_opname'   => now(),
                            'jenis_opname'     => 'keluar',
                            'jumlah_opname'    => $jumlah,
                            'no_bukti'         => $laporan->no_laporan . '/' . $perbaikan->id_perbaikan,
                            'keterangan'       => 'Barang digunakan untuk perbaikan (update)',
                            'created_by'       => $user->username ?? 'system',
                        ]);
                    }
                }
            }

            // Barang rusak (jenis_opname = rusak)
            if ($request->has('barang_rusak_id')) {
                foreach ($request->barang_rusak_id as $i => $rusakId) {
                    if (!$rusakId) continue;
                    $jumlah = $request->jumlah_barang_rusak[$i] ?? 1;
                    $keterangan = $request->keterangan_rusak[$i] ?? '-';

                    $detailRusak = DetailBarang::find($rusakId);
                    if ($detailRusak) {
                        StockOpname::create([
                            'detail_barang_id' => $detailRusak->id_detail_barang,
                            'tanggal_opname'   => now(),
                            'jenis_opname'     => 'rusak',
                            'jumlah_opname'    => $jumlah,
                            'no_bukti'         => $laporan->no_laporan . '/' . $perbaikan->id_perbaikan,
                            'keterangan'       => $keterangan,
                            'created_by'       => $user->username ?? 'system',
                        ]);
                    }
                }
            }

            // Log perubahan utama
            $logs[] = fn() => Helper::createLog(
                $perbaikan,
                'updated',
                $this->logContext,
                'Memperbarui data perbaikan: ' . $perbaikan->id_perbaikan
            );

            Helper::createBatchLogs(...array_filter($logs));
            DB::commit();

            return JsonResponseHelper::success($perbaikan, JsonResponseHelper::$SUCCESS_UPDATE, 200);
        } catch (ValidationException $error) {
            DB::rollBack();
            return JsonResponseHelper::error($error->errors(), JsonResponseHelper::$FAILED_VALIDATE, 422);
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_UPDATE . " " . $e->getMessage());
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
        DB::beginTransaction();

        try {
            $perbaikan = Perbaikan::with(['transBarangPerbaikan', 'dokPerbaikan', 'laporan'])->findOrFail($id);

            // --- DATA LAMA ---
            $oldPerbaikan = clone $perbaikan;
            $oldTrans     = $perbaikan->transBarangPerbaikan->map(fn($t) => clone $t);
            $oldDok       = $perbaikan->dokPerbaikan->map(fn($d) => clone $d);
            $oldStock     = StockOpname::where('no_bukti', $perbaikan->laporan->no_laporan . '/' . $perbaikan->id_perbaikan)->get()->map(fn($s) => clone $s);

            // --- 1. Kembalikan stok & hapus transaksi lama ---
            foreach ($perbaikan->transBarangPerbaikan as $trans) {
                if ($barang = Barang::find($trans->barang_id)) {
                    $barang->stok_sekarang += $trans->jumlah_barang;
                    $barang->save();
                }
            }
            TransPerbaikanBarang::where('perbaikan_id', $perbaikan->id_perbaikan)->delete();

            // --- 2. Hapus stok opname ---
            StockOpname::where('no_bukti', $perbaikan->laporan->no_laporan . '/' . $perbaikan->id_perbaikan)->delete();

            // --- 3. Hapus dokumen ---
            foreach ($perbaikan->dokPerbaikan as $dok) {
                if ($dok->file_perbaikan && Storage::disk('public')->exists($dok->file_perbaikan)) {
                    Storage::disk('public')->delete($dok->file_perbaikan);
                }
                $dok->delete();
            }

            // --- 4. Hapus perbaikan utama ---
            $laporan = $perbaikan->laporan;
            $perbaikan->delete();

            // --- 5. Update status laporan sesuai perbaikan terakhir ---
            $lastPerbaikan = Perbaikan::where('laporan_id', $laporan->id_laporan)
                ->orderBy('id_perbaikan', 'desc')
                ->first();

            if ($lastPerbaikan) {
                // Jika masih ada perbaikan sebelumnya, ambil status_progress-nya
                $laporan->status_laporan = $lastPerbaikan->status_progress;
            } else {
                // Jika tidak ada lagi, berarti belum selesai → status dikembalikan ke "proses"
                $laporan->status_laporan = 'proses';
            }

            $laporan->save();

            // --- 6. LOGGING ---
            $logs = [];

            $logs[] = fn() => Helper::createLog(
                $perbaikan,
                'deleted',
                $this->logContext,
                'Menghapus data perbaikan: ' . $oldPerbaikan->laporan_id,
                $oldPerbaikan,
                []
            );

            foreach ($oldTrans as $old) {
                $logs[] = fn() => Helper::createLog(
                    $old,
                    'deleted',
                    $this->logContext3,
                    'Menghapus TransPerbaikanBarang: ' . $old->barang_id,
                    $old,
                    []
                );
            }

            foreach ($oldStock as $old) {
                $logs[] = fn() => Helper::createLog(
                    $old,
                    'deleted',
                    $this->logContext4,
                    'Menghapus StockOpname: ' . $old->no_bukti,
                    $old,
                    []
                );
            }

            foreach ($oldDok as $old) {
                $logs[] = fn() => Helper::createLog(
                    $old,
                    'deleted',
                    $this->logContext2,
                    'Menghapus DokPerbaikan: ' . $old->perbaikan_id,
                    $old,
                    []
                );
            }

            Helper::createBatchLogs(...array_filter($logs));

            DB::commit();
            return JsonResponseHelper::success($perbaikan, JsonResponseHelper::$SUCCESS_DELETE, 200);
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            return JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_DELETE);
        }
    }

    public function cek($id)
    {
        $laporan = Laporan::where('pelapor_id', $id)->first();
        $data['title'] = "Perbaikan";
        $data['subtitle'] = "Status Perbaikan";
        // $data['laporan'] = Laporan::all();
        // $data['barang'] = Barang::all();
        $data['pelapor'] = Pelapor::findorFail($id);
        $data['laporan'] = $laporan;
        $data['doklaporan'] = DokLaporan::where('laporan_id', $laporan->id_laporan)->get();
        $data['teknisi'] = Teknisi::all();
        return view('admin.perbaikan.cek_status', $data);
    }

    public function cek_post(Request $request, $id)
    {
        allowOnlyAjax($request);
        try {
            if ($request->status_laporan == 'ditolak') {
                $validated = $request->validate([
                    'ket_tolak' => 'required|string',
                    'status_laporan' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    'teknisi_id' => 'required',
                    'status_laporan' => 'required',
                ]);
            }

            DB::beginTransaction();
            $data = Laporan::where('pelapor_id', $id)->get()->first();
            $oldData = clone $data;

            $data->update($validated);
            $newData = clone $data;
            Helper::createLog($data, 'updated',  $this->logContext2, 'mengubah data laporan status: ' . $newData->status_laporan . ' dan teknisi : ' . $newData->teknisi_id, $oldData, $newData);
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
}
