<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Jalan;
use App\Models\JenisAsset;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PenanggungJawab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        do {
            $kode_asset = 'ASSET-' . str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Asset::where('kode_asset', $kode_asset)->exists());

        $kecamatan = Kecamatan::where('id_kecamatan', $row['kecamatan'] ?? '')->first();
        $kelurahan = Kelurahan::where('id_kelurahan', $row['kelurahan_desa'] ?? '')->first();
        $penanggungJawab = PenanggungJawab::where('id_penanggung_jawab', $row['penanggung_jawab'] ?? '')->first();
        $jenis_asset = JenisAsset::where('id_jenis_asset', $row['jenis_asset'] ?? '')->first();
        $jalan = Jalan::where('id_jalan', $row['nama_jalan'] ?? '')->first();

        return new Asset([
            'kode_asset' => $kode_asset,
            'jenis_asset_id' => $jenis_asset->id_jenis_asset ?? null,
            'penanggung_jawab_id' => $penanggungJawab->id_penanggung_jawab ?? null,
            'jalan_id' => $jalan->id_jalan ?? null,
            'nama_asset' => $row['nama_asset'] ?? null,
            'koordinat' => $row['koordinat'] ?? null,
            'kondisi' => $row['kondisi'] ?? null,
            'kecamatan_id' => $kecamatan->id_kecamatan ?? null,
            'kelurahan_id' => $kelurahan->id_kelurahan ?? null,
        ]);
    }
}
