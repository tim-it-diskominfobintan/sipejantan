<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Asset;
use App\Helpers\Helper;
use App\Models\Laporan;
use App\Models\Pelapor;
use App\Models\Teknisi;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\DokLaporan;
use App\Models\JenisAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JsonResponseHelper;
use Illuminate\Validation\ValidationException;

class PengaduanController extends Controller
{
    private $logContext = 'Pelapor';
    private $logContext2 = 'Laporan';

    public function index()
    {
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset', 'kondisi')->get();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $title = 'Pengaduan Aset Dinas Perhubungan Kabupaten Bintan';
        $jenis_asset = JenisAsset::all();
        return view('home', compact('assets', 'kecamatan', 'kelurahan', 'title', 'jenis_asset'));
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
        allowOnlyAjax($request);

        $rules = [
            'nik'               => 'required|string|max:20',
            'nama'              => 'required|string|max:150',
            'email'             => 'required|email|max:150',
            'no_hp'             => 'required|string|max:15',
            'foto_identitas'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'kecamatan_id'     => 'required',
            'kelurahan_id'     => 'required',
            'alamat'           => 'required',

            'no_laporan'        => 'required|string|max:50|unique:laporan,no_laporan',
            'asset_id'          => 'required|exists:asset,id_asset',
            'deskripsi_laporan' => 'required|string',
            'foto_laporan'      => 'required',
            'foto_laporan.*'      => 'image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        $cek = Laporan::where('asset_id', $validated['asset_id'])->whereIn('status_laporan', ['proses', 'pending', 'diterima'])->first();
        if ($cek) {
            return JsonResponseHelper::info('', 'Aset sedang dalam proses pelaporan.', 500);
        }
        // cek apakah asset sedang dalam perbaikan atau sudah selesai, jika sudah selesai maka terima laporan, jika tidak maka alert asset sedang dalam perbaikan
        try {

            $fotoIdentitasPath = $request->file('foto_identitas')->store('identitas', 'public');
            $pelapor = new Pelapor();
            $pelapor->nik            = $validated['nik'];
            $pelapor->nama           = $validated['nama'];
            $pelapor->email          = $validated['email'];
            $pelapor->no_hp          = $validated['no_hp'];
            $pelapor->kecamatan_id   = $validated['kecamatan_id'];
            $pelapor->kelurahan_id   = $validated['kelurahan_id'];
            $pelapor->alamat         = $validated['alamat'];
            $pelapor->foto_identitas = $fotoIdentitasPath;
            $pelapor->created_by     = 'umum';
            $pelapor->save();

            $logs[] = fn() => Helper::createLog($pelapor, 'created', $this->logContext, 'menambahkan data pelapor dari masyarakat : ' . $pelapor->nama);

            // Simpan Laporan
            $laporan = new Laporan();
            $laporan->no_laporan        = $validated['no_laporan'];
            $laporan->pelapor_id        = $pelapor->id_pelapor;
            $laporan->asset_id          = $validated['asset_id'];
            $laporan->deskripsi_laporan = $validated['deskripsi_laporan'];
            $laporan->tanggal_laporan   = date('Y-m-d');
            $laporan->status_laporan    = 'pending';
            $laporan->created_by        = 'umum';
            $laporan->teknisi_id        = null;
            $laporan->save();

            // if ($request->hasFile('foto_laporan')) {
            //     $path2 = $request->file('foto_laporan')->store('foto_laporan', 'public');
            //     $laporan->foto_laporan = $path2;
            // }

            if ($request->hasFile('foto_laporan')) {
                foreach ($request->file('foto_laporan') as $file) {
                    $path2 = $file->store('foto_laporan', 'public');

                    $dok = new DokLaporan();
                    $dok->laporan_id  = $laporan->id_laporan;
                    $dok->file_laporan = $path2;
                    $dok->created_by   = 'umum';
                    $dok->save();
                }
            }

            $logs[] = fn() => Helper::createLog($laporan, 'created', $this->logContext2, 'menambahkan data laporan dengan no laporan: ' . $laporan->no_laporan);

            Helper::createBatchLogs(...array_filter($logs));
            DB::commit();

            return JsonResponseHelper::success([
                'laporan'    => $laporan,
            ], 'Laporan anda akan segera kami proses, Terima Kasih.', 201);
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
    public function show(Request $request)
    {
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'dokumen', 'latestLaporan'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset', 'kondisi')->get();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $title = 'Pengaduan Aset Dinas Perhubungan Kabupaten Bintan';
        $jenis_asset = JenisAsset::all();
        return view('guest.pengaduan', compact('assets', 'kecamatan', 'kelurahan', 'title', 'jenis_asset'));
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

    public function getKelurahan($id)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $id)->get();
        return response()->json($kelurahan);
    }

    public function success($no_laporan)
    {
        $assets = Asset::with(['penanggung_jawab', 'jenis_asset', 'jalan', 'laporan'])->select('id_asset', 'kode_asset', 'nama_asset', 'koordinat', 'jalan_id', 'jenis_asset_id', 'penanggung_jawab_id', 'foto_asset', 'kondisi')->get();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $title = 'Pengaduan Aset Dinas Perhubungan Kabupaten Bintan';
        $jenis_asset = JenisAsset::all();
        return view('guest.success', compact('no_laporan', 'assets', 'kecamatan', 'kelurahan', 'title', 'jenis_asset'));
    }
}
