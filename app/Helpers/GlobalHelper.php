<?php

use App\Models\Laporan;
use App\Models\StockOpname;

/*
    |--------------------------------------------------------------------------
    | Global Helper
    |--------------------------------------------------------------------------
    |
    | Global Helper adalah helper yang tidak perlu class, bisa langsung dipakai, dan bisa lintas framework.
    | Bisa dipakai di CI, Laravel, Symfony dsb. Cara pakainya tinggal tulis 'encryptUrl()', dll.
    | Jika anda ingin menggunakan versi class, tambahkan saja di app/Helpers/Helper.
    |
    |
*/

if (!function_exists('encryptUrl')) {
    function encryptUrl($string)
    {
        $output = false;

        // $security = parse_ini_file('security.ini');
        $secret_key = 'K*qkMZrqp7pjVmD-';
        $secret_iv = 'n6dNXNb+*Q_KR^p8';
        $encrypt_method = 'aes-256-cbc';

        // hash
        $key = hash('sha256', $secret_key);

        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encryption given text/string/number
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);

        return $output;
    }
}

if (!function_exists('decryptUrl')) {
    function decryptUrl($string)
    {
        $output = false;

        // $security = parse_ini_file('security.ini');
        $secret_key = 'K*qkMZrqp7pjVmD-';
        $secret_iv = 'n6dNXNb+*Q_KR^p8';
        $encrypt_method = 'aes-256-cbc';

        // hash
        $key = hash('sha256', $secret_key);

        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the decryption given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

        return $output;
    }
}

// untuk cek string apakah ada url (boolean), misal 'halo coba cek link ini https://bintankab.go.id' -> true, 'halo dek' -> false
if (!function_exists('containsUrl')) {
    function containsUrl($str)
    {
        $matches = [];
        $pattern = '/\b(?:https?|ftp|www)(:\/\/)*[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
        preg_match_all($pattern, $str, $matches);
        return $matches[0];
    }
}

// untuk mengecek apakah ini sedang di localhost atau bukan (boolean), bisa untuk skip capctha, password dll
if (!function_exists('isLocalhost')) {
    function isLocalhost()
    {
        $whitelist_ips = ['127.0.0.1', '::1'];
        $whitelist_hosts = ['localhost', 'localhost:8000'];

        $remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';
        $server_name = $_SERVER['SERVER_NAME'] ?? '';
        $http_host   = $_SERVER['HTTP_HOST'] ?? '';

        return in_array($remote_addr, $whitelist_ips) ||
            in_array($server_name, $whitelist_hosts) ||
            in_array($http_host, $whitelist_hosts);
    }
}

// dari datetime PHP/MYSQL ke date time indo: 2025-06-10 14:30:00 -> 10 Juni 2025 14:30:00
// --OTOMATIS DETEKSI JAM
if (!function_exists('dateTimeIndo')) {
    function dateTimeIndo($tgl)
    {
        if (strtotime($tgl) !== false) {
            $timestamp = strtotime($tgl);
        } elseif (is_numeric($tgl) && (int)$tgl > 0) {
            $timestamp = (int)$tgl;
        } else {
            return "Invalid datetime value";
        }

        $tanggal = date('d', $timestamp);
        $bulan   = getBulan(date('n', $timestamp));
        $tahun   = date('Y', $timestamp);
        $jam     = date('H:i:s', $timestamp);

        // Cek apakah input string mengandung jam
        $hasTime = strpos($tgl, ':') !== false;

        $result = $tanggal . ' ' . $bulan . ' ' . $tahun;
        if ($hasTime) {
            $result .= ' ' . $jam;
        }

        return $result;
    }
}

// dari datetime PHP/MYSQL ke date time indo: 2025-06-10 14:30:00 -> 10 Juni 2025 14:30:00
// --OTOMATIS DETEKSI JAM
if (!function_exists('dateTimeShortMonthIndo')) {
    function dateTimeShortMonthIndo($tgl)
    {
        if (strtotime($tgl) !== false) {
            $timestamp = strtotime($tgl);
        } elseif (is_numeric($tgl) && (int)$tgl > 0) {
            $timestamp = (int)$tgl;
        } else {
            return "Invalid datetime value";
        }

        $tanggal = date('d', $timestamp);
        $bulan   = getBulanShort(date('n', $timestamp));
        $tahun   = date('Y', $timestamp);
        $jam     = date('H:i:s', $timestamp);

        // Cek apakah input string mengandung jam
        $hasTime = strpos($tgl, ':') !== false;

        $result = $tanggal . ' ' . $bulan . ' ' . $tahun;
        if ($hasTime) {
            $result .= ' ' . $jam;
        }

        return $result;
    }
}


// dari angka ke bulan: 1 -> Januari, 12 -> Februari;
if (!function_exists('getBulan')) {
    function getBulan($bln)
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $bulan[$bln] ?? 'Bulan tidak valid!';
    }
}

// dari angka ke bulan: 1 -> Jan, 12 -> Feb;
if (!function_exists('getBulanShort')) {
    function getBulanShort($bln)
    {
        $bulan = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ];
        return $bulan[$bln] ?? 'Bulan tidak valid!';
    }
}

// dari english day ke day indo: Sunday -> Minggu;
if (!function_exists('getNamaHari')) {
    function getNamaHari($timestamp)
    {
        $hari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];
        return $hari[date('l', $timestamp)] ?? 'Hari tidak valid!';
    }
}

// 2025-06-10 05:00:00 -> Selasa, 10 Juni 2025 05:00:00
// --OTOMATIS DETEKSI JAM
if (!function_exists('longDateIndo')) {
    function longDateIndo($tgl)
    {
        $timestamp = strtotime($tgl);
        if ($timestamp === false) return 'Invalid date';

        // Deteksi apakah input mengandung jam
        $hasTime = strpos($tgl, ':') !== false;

        $hari     = getNamaHari($timestamp);
        $tanggal  = date('d', $timestamp);
        $bulan    = getBulan(date('n', $timestamp));
        $tahun    = date('Y', $timestamp);
        $jam      = date('H:i:s', $timestamp);

        return $hari . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun . ($hasTime ? ' ' . $jam : '');
    }
}

// Dimas Nugroho Putro -> Dimas
if (!function_exists('getFirstName')) {
    function getFirstName($nama)
    {
        if (trim($nama) === '') {
            return '';
        }

        $parts = explode(' ', trim($nama));
        return $parts[0];
    }
}

if (!function_exists('getOS')) {
    function getOS($userAgent)
    {
        // Deteksi platform sederhana
        if (stripos($userAgent, 'windows') !== false) return 'Windows';
        if (stripos($userAgent, 'mac') !== false) return 'MacOS';
        if (stripos($userAgent, 'linux') !== false) return 'Linux';
        if (stripos($userAgent, 'android') !== false) return 'Android';
        if (stripos($userAgent, 'iphone') !== false) return 'iOS';
        return 'Unknown';
    }
}

if (!function_exists('getBrowser')) {
    function getBrowser($userAgent)
    {
        if (stripos($userAgent, 'chrome') !== false) return 'Chrome';
        if (stripos($userAgent, 'firefox') !== false) return 'Firefox';
        if (stripos($userAgent, 'safari') !== false && stripos($userAgent, 'chrome') === false) return 'Safari';
        if (stripos($userAgent, 'edge') !== false) return 'Edge';
        return 'Unknown';
    }
}

if (!function_exists('getBrowser')) {
    function getBrowser($userAgent)
    {
        if (stripos($userAgent, 'chrome') !== false) return 'Chrome';
        if (stripos($userAgent, 'firefox') !== false) return 'Firefox';
        if (stripos($userAgent, 'safari') !== false && stripos($userAgent, 'chrome') === false) return 'Safari';
        if (stripos($userAgent, 'edge') !== false) return 'Edge';
        return 'Unknown';
    }
}

if (!function_exists('getMimeTypeFromExtension')) {
    function getMimeTypeFromExtension($extension)
    {
        $mimes = [
            'js'    => 'application/javascript',
            'css'   => 'text/css',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'svg'   => 'image/svg+xml',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'map'   => 'application/json',
            'json'  => 'application/json',
        ];

        return $mimes[$extension] ?? 'application/octet-stream';
    }
}

if (! function_exists('countLaporanByKecamatan')) {
    function countLaporanByKecamatan($kecamatanId, $status)
    {
        return Laporan::whereHas('asset', function ($q) use ($kecamatanId) {
            $q->where('kecamatan_id', $kecamatanId);
        })->where('status_laporan', $status)->count();
    }
}

if (! function_exists('CekStokAwal')) {
    function CekStokAwal($detail_barang_id)
    {
        $stokAwalMasuk = StockOpname::where('detail_barang_id', $detail_barang_id)
            ->where('jenis_opname', 'masuk')
            ->orderBy('tanggal_opname', 'asc')
            ->first();
        $stokAwal = $stokAwalMasuk ? $stokAwalMasuk->jumlah_opname : 0;
        return $stokAwal;
    }
}

if (! function_exists('CekStok')) {
    function CekStok($detail_barang_id)
    {
        $stokMasuk = StockOpname::where('detail_barang_id', $detail_barang_id)
            ->where('jenis_opname', 'masuk')
            ->sum('jumlah_opname');

        $stokKeluar = StockOpname::where('detail_barang_id', $detail_barang_id)
            ->where('jenis_opname', 'keluar')
            ->sum('jumlah_opname');

        $stok = $stokMasuk - $stokKeluar;

        return $stok;
    }
}

if (! function_exists('stokAwalBarangId')) {
    function stokAwalBarangId($barangId)
    {
        $stokAwal = StockOpname::with('detailbarang')
            ->whereHas('detailbarang', function ($q) use ($barangId) {
                $q->where('barang_id', $barangId);
            })
            ->where('jenis_opname', 'masuk')
            ->orderBy('tanggal_opname', 'asc')
            ->first();

        return $stokAwal ? $stokAwal->jumlah_opname : 0;
    }
}

if (! function_exists('stokSekrangBarangId')) {
    function stokSekrangBarangId($barangId)
    {
        $stokSekarang = StockOpname::whereHas('detailbarang', function ($q) use ($barangId) {
            $q->where('barang_id', $barangId);
        })
            ->selectRaw("SUM(CASE WHEN jenis_opname = 'masuk' THEN jumlah_opname 
                WHEN jenis_opname = 'keluar' THEN - jumlah_opname 
                ELSE 0 END) as total_stok")
            ->first();

        return $stokSekarang = $stokSekarang->total_stok ?? 0;
    }
}
