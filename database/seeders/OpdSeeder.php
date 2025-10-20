<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $opd = [
            [
                'id' => '1',
                'nama' => 'Pegawai Pindah',
                'alias' => 'pegawaipindah',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '2',
                'nama' => 'Dinas Komunikasi dan Informatika',
                'alias' => 'diskominfo',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '3',
                'nama' => 'Sekretariat Daerah',
                'alias' => 'sekda',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '4',
                'nama' => 'Dinas Kesehatan',
                'alias' => 'dinkes',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '5',
                'nama' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
                'alias' => 'bkpsdm',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '6',
                'nama' => 'UPTD RSUD Bintan',
                'alias' => 'rsud',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '7',
                'nama' => 'UPTD Balai Pengelolaan Farmasi dan Alat Kesehatan',
                'alias' => 'balaifarmasi',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '8',
                'nama' => 'UPTD Puskesmas Kijang',
                'alias' => 'pkmkijang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '9',
                'nama' => 'UPTD Puskesmas Sei. Lekop',
                'alias' => 'pkmseilekop',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '10',
                'nama' => 'UPTD Puskesmas Kelong',
                'alias' => 'pkmkelong',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '11',
                'nama' => 'UPTD Puskesmas Numbing',
                'alias' => 'pkmnumbing',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '14',
                'nama' => 'UPTD Puskesmas Toapaya',
                'alias' => 'pkmtoapaya',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '15',
                'nama' => 'UPTD Puskesmas Mantang',
                'alias' => 'pkmantang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '17',
                'nama' => 'UPTD Puskesmas Kawal',
                'alias' => 'pkmkawal',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '18',
                'nama' => 'UPTD Puskesmas Teluk Sebong',
                'alias' => 'pkmteluksebong',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '19',
                'nama' => 'UPTD Puskesmas Sri Bintan',
                'alias' => 'pkmsribintan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '20',
                'nama' => 'UPTD Puskesmas Berakit',
                'alias' => 'pkmberakit',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '21',
                'nama' => 'UPTD Puskesmas Teluk Bintan',
                'alias' => 'pkmtelukbintan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '22',
                'nama' => 'UPTD Puskesmas Kuala Sempang',
                'alias' => 'pkmkualasempang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '23',
                'nama' => 'UPTD Puskesmas Teluk Sasah',
                'alias' => 'pkmteluksasah',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '24',
                'nama' => 'UPTD Puskesmas Tanjung Uban',
                'alias' => 'pkmtanjunguban',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '25',
                'nama' => 'UPTD Puskesmas Tambelan',
                'alias' => 'pkmtambelan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '26',
                'nama' => 'Inspektorat Daerah',
                'alias' => 'inspektorat',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '27',
                'nama' => 'Dinas Pemberdayaan Masyarakat Desa',
                'alias' => 'dpmd',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '28',
                'nama' => 'Dinas Pegawai Trial',
                'alias' => 'trial',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '29',
                'nama' => 'Dinas Pemberdayaan Perempuan, Perlindungan Anak, Pengendalian Penduduk dan Keluarga Berencana',
                'alias' => 'dp3kb',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '30',
                'nama' => 'Kecamatan Teluk Sebong',
                'alias' => 'kecteluksebong',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '31',
                'nama' => 'Kecamatan Toapaya',
                'alias' => 'kectoapaya',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '32',
                'nama' => 'Kecamatan Teluk Bintan',
                'alias' => 'kectelukbintan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '33',
                'nama' => 'Kecamatan Bintan Utara',
                'alias' => 'kecbintanutara',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '34',
                'nama' => 'Kecamatan Bintan Timur',
                'alias' => 'kecbintantimur',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '35',
                'nama' => 'Kecamatan Bintan Pesisir',
                'alias' => 'kecbintanpesisir',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '36',
                'nama' => 'Kecamatan Gunung Kijang',
                'alias' => 'kecgunungkijang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '37',
                'nama' => 'Kecamatan Mantang',
                'alias' => 'kecmantang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '38',
                'nama' => 'Kecamatan Tambelan',
                'alias' => 'kectambelan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '39',
                'nama' => 'Kecamatan Seri Kuala Lobam',
                'alias' => 'kecserikualalobam',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '40',
                'nama' => 'Sekretariat DPRD',
                'alias' => 'setda',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '41',
                'nama' => 'Badan Penanggulangan Bencana Daerah',
                'alias' => 'bpbd',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '42',
                'nama' => 'Badan Pendapatan Daerah',
                'alias' => 'bapenda',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '43',
                'nama' => 'Badan Keuangan dan Aset Daerah',
                'alias' => 'bkad',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '45',
                'nama' => 'Badan Perencanaan, Penelitian dan Pengembangan Daerah',
                'alias' => 'bapelitbang',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '46',
                'nama' => 'Badan Kesatuan Bangsa dan Politik',
                'alias' => 'kesbangpol',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '49',
                'nama' => 'Dinas Perpustakaan dan Arsip Daerah',
                'alias' => 'dpad',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '50',
                'nama' => 'Dinas Penanaman Modal Pelayanan Terpadu Satu Pintu',
                'alias' => 'dpmptsp',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '51',
                'nama' => 'Dinas Kependudukan dan Catatan Sipil',
                'alias' => 'disdukcapil',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '52',
                'nama' => 'Dinas Pekerjaan Umum, Penataan Ruang dan Pertanahan',
                'alias' => 'dpupr',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '53',
                'nama' => 'Dinas Perhubungan',
                'alias' => 'dishub',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '54',
                'nama' => 'Dinas Koperasi, Usaha Mikro, Perindustrian dan Perdagangan',
                'alias' => 'dkupp',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '55',
                'nama' => 'Dinas Tenaga Kerja',
                'alias' => 'disnaker',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '56',
                'nama' => 'Dinas Kebudayaan dan Pariwisata',
                'alias' => 'disbudpar',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '57',
                'nama' => 'Dinas Perumahan dan Kawasan Permukiman',
                'alias' => 'disperkim',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '58',
                'nama' => 'Dinas Sosial',
                'alias' => 'dinsos',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '59',
                'nama' => 'Dinas Perikanan',
                'alias' => 'diskan',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '60',
                'nama' => 'Dinas Kepemudaan dan Olahraga',
                'alias' => 'dispora',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '61',
                'nama' => 'Dinas Lingkungan Hidup',
                'alias' => 'dlh',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '62',
                'nama' => 'Dinas Ketahanan Pangan dan Pertanian',
                'alias' => 'dkpp',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '64',
                'nama' => 'Dinas Pendidikan',
                'alias' => 'disdik',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ],
            [
                'id' => '65',
                'nama' => 'Satuan Polisi Pamong Praja',
                'alias' => 'satpolpp',
                'logo' => null,
                'created_at' => '2025-01-22 12:33:18',
                'updated_at' => '2025-01-22 12:33:18'
            ]
        ];

        foreach ($opd as $d) {

            $d = [
                ...$d,
            ];

            $check_logo_opd = public_path('assets/global/img/logo_opd/' . $d['alias'] . '.png');

            Storage::disk('public')->makeDirectory('opd/logo');

            if (file_exists($check_logo_opd)) {
                File::copy(
                    public_path('assets/global/img/logo_opd/' . $d['alias'] . '.png'),
                    storage_path('app/public/opd/logo/' . $d['alias'] . '.png')
                );
                $d['logo'] = 'opd/logo/' . $d['alias'] . '.png';
            }

            Opd::create($d);
        }
    }
}
