<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['title'] = "Dashboard";
        $data['kecamatan'] = Kecamatan::all();
        $data['subtitle'] = "Pantau summary data disini.";
       
        return view('admin.dashboard.index', $data);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function asset()
    {
        $data = Asset::with('penanggung_jawab', 'jenis_asset', 'jalan', 'laporan', 'dokumen')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }
}
