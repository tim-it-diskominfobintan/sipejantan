<?php

namespace App\Helpers;

use Throwable;
use GuzzleHttp\Client;
use App\Models\StockOpname;
use Jenssegers\Agent\Agent;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mime\MimeTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Facades\LogBatch;

/*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    |
    | Ini khusus helper yang perlu class, harus use/import classnya dulu baru bisa dipakai.
    | Gunanya untuk load di controller, model, atau view. Contohnya: Helper::insertLog().
    | Kalo mau versi function aja, silahkan tambahkan di \App\Helpers\GlobalHelpers.
    |
    |
*/

class Helper
{
    public static function createLog(
        Model $model,
        string $event,
        string $logName,
        ?string $description = null,
        array|Model $oldData = [],
        array|Model $newData = [],
        array $extraProperties = []
    ): void {
        try {
            // Mulai batch log
            LogBatch::startBatch();

            // (opsional) simpan batch_uuid ke container
            $batchUuid = LogBatch::getUuid();
            app()->instance('current_activity_batch_uuid', $batchUuid);

            $allowedEvents = ['viewed', 'created', 'updated', 'deleted', 'logged_in', 'logged_out'];

            if (!in_array($event, $allowedEvents)) {
                throw new \InvalidArgumentException("Event harus salah satu dari: " . implode(', ', $allowedEvents));
            }

            $user = Auth::user();
            $username = $user?->username ?? 'Seseorang tanpa sesi login';

            // Ubah ke array jika masih model
            if ($oldData instanceof Model) {
                $oldData = $oldData->getAttributes();
            }

            if ($newData instanceof Model) {
                $newData = $newData->getAttributes();
            }

            // Ambil dari model kalau kosong
            $original = $oldData ?: $model->getOriginal();
            $current  = $newData ?: $model->getAttributes();

            // Hitung hanya perubahan
            $changes = array_diff_assoc($current, $original);

            // Buat deskripsi otomatis kalau tidak disediakan
            $defaultDescription = match ($event) {
                'created' => "$username menambahkan " . class_basename($model),
                'updated' => "$username mengubah " . class_basename($model),
                'deleted' => "$username menghapus " . class_basename($model),
                'viewed'  => "$username melihat " . class_basename($model),
                'logged_in'  => "$username login ke sistem",
                'logged_out' => "$username logout dari sistem",
                default   => "$username melakukan $event pada " . class_basename($model),
            };

            $logDescription = $description ?: $defaultDescription;

            // Siapkan properti log
            $properties = [];

            if ($event === 'created') {
                $properties['new'] = $newData;
            } elseif ($event === 'viewed') {
                $properties['new'] = $newData;
            } else {
                $properties['new'] = $newData;
                $properties['old'] = $oldData;
                $properties['changes'] = [
                    'new' => $changes,
                    'old' => array_intersect_key($original, $changes),
                ];
            }

            $batchUuid = app()->bound('current_activity_batch_uuid')
                ? app('current_activity_batch_uuid')
                : null;

            $finalProperties = array_merge([
                'batch_uuid' => $batchUuid,
            ], $properties, $extraProperties);

            activity($logName)
                ->causedBy($user)
                ->performedOn($model)
                ->event($event)
                ->withRequestInfo($finalProperties)
                ->log($logDescription);

            // Tambahkan summary batch ke activity_log_batches jika belum ada
            if ($batchUuid && DB::table('activity_log_batches')->where('batch_uuid', $batchUuid)->exists()) {
                $count = Activity::where('batch_uuid', $batchUuid)->count();
                $latest = Activity::where('batch_uuid', $batchUuid)->max('created_at');

                DB::table('activity_log_batches')
                    ->where('batch_uuid', $batchUuid)
                    ->update([
                        'batch_uuid' => $batchUuid,
                        'log_count' => $count,
                        'latest_log_at' => $latest,
                        'updated_at' => now(),
                    ]);
            }

            if ($batchUuid && !DB::table('activity_log_batches')->where('batch_uuid', $batchUuid)->exists()) {
                $count = Activity::where('batch_uuid', $batchUuid)->count();
                $latest = Activity::where('batch_uuid', $batchUuid)->max('created_at');

                DB::table('activity_log_batches')->insert([
                    'batch_uuid' => $batchUuid,
                    'log_count' => $count,
                    'latest_log_at' => $latest,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Akhiri batch log
            LogBatch::endBatch();

            // (opsional) hapus dari container
            app()->forgetInstance('current_activity_batch_uuid');
        } catch (Throwable $e) {

            throw $e;
            // Akhiri batch log
            LogBatch::endBatch();

            // (opsional) hapus dari container
            app()->forgetInstance('current_activity_batch_uuid');
        }
    }

    public static function createBatchLogs(callable ...$logCallbacks)
    {
        // Mulai batch log
        LogBatch::startBatch();

        // (opsional) simpan batch_uuid ke container
        $batchUuid = LogBatch::getUuid();
        app()->instance('current_activity_batch_uuid', $batchUuid);

        // Jalankan semua log callback
        foreach ($logCallbacks as $callback) {
            $callback(); // ini harus callable, seperti: fn() => ...
        }

        // Akhiri batch log
        LogBatch::endBatch();

        // (opsional) hapus dari container
        app()->forgetInstance('current_activity_batch_uuid');
    }

    public static function createLoginLog($user, Request $request, $status = 'success', $authProvider = 'self')
    {
        LoginAttempt::create([
            'user_id'    => optional($user)->id,
            'username'   => $request->input('email'),
            'login_at'   => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'platform'   => getOS($request->userAgent()),
            'browser'    => getBrowser($request->userAgent()),
            'status'     => $status,
            'auth_provider' => $authProvider
        ]);
    }

    public static function createLogoutLog()
    {
        LoginAttempt::where('user_id', auth()->user()->id)
            ->latest()
            ->whereNull('logout_at')
            ->update(['logout_at' => now()]);
    }

    public static function storeFile($file, $path, $fileName = null, $disk = null)
    {
        $disk = $disk ?? env('FILESYSTEM_DRIVER', 'public');

        if ($fileName === null) {
            $fileName = time() . '_' . $file->getClientOriginalName();
        }

        $filePathUploaded = Storage::disk($disk)->putFileAs($path, $file, $fileName);

        return $filePathUploaded;
    }

    public static function storeFileFromUrl($fileUrl, $additionalHeaders = [], $path, $fileName = null, $disk = null)
    {
        $disk = $disk ?? env('FILESYSTEM_DRIVER', 'public');
        $additionalHeaders = $additionalHeaders == null ? [] : $additionalHeaders;

        $client = new Client();
        $response = $client->get($fileUrl, [
            'headers' => [
                'accept' => 'application/octet-stream',
                ...$additionalHeaders
            ],
            'stream' => true,
            'http_errors' => false,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("Gagal mengunduh file dari URL: {$fileUrl} (status {$response->getStatusCode()})");
        }

        // Ambil isi
        $bodyStream = $response->getBody();
        $contents = $bodyStream->getContents();

        $finalName = $fileName;

        // 2. Kalau belum, coba dari Content-Disposition
        if (!$finalName) {
            $disposition = $response->getHeaderLine('Content-Disposition');
            if ($disposition) {
                if (preg_match('/filename\*=UTF-8\'\'(?P<name>[^;]+)/', $disposition, $m)) {
                    $finalName = rawurldecode($m['name']);
                } elseif (preg_match('/filename="?(?P<name>[^\";]+)"?/', $disposition, $m)) {
                    $finalName = $m['name'];
                }
            }
        }

        // 3. Kalau masih belum, ambil basename dari URL
        if (!$finalName) {
            $parsed = parse_url($fileUrl, PHP_URL_PATH) ?: '';
            $base = basename($parsed);
            if ($base && $base !== '/') {
                $finalName = $base;
            }
        }

        // 4. Kalau belum ada nama, buat random
        if (!$finalName) {
            $finalName = uniqid('file_', true);
        }

        // Normalize / sanitize nama
        $finalName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $finalName);

        // Ekstrak ekstensi
        $extension = pathinfo($finalName, PATHINFO_EXTENSION);

        // 5. Kalau nggak ada ekstensi, coba tebak dari content-type
        if (!$extension) {
            $contentType = $response->getHeaderLine('Content-Type');
            if ($contentType) {
                $mimeTypes = new MimeTypes();
                $exts = $mimeTypes->getExtensions($contentType);
                if (!empty($exts)) {
                    $extension = $exts[0];
                }
            }
        }

        // 6. Fallback ekstensi kalau masih kosong
        if (!$extension) {
            $extension = 'bin';
        }


        // Pastikan nama punya ekstensi
        if (pathinfo($finalName, PATHINFO_EXTENSION) !== $extension) {
            $finalName = pathinfo($finalName, PATHINFO_FILENAME) . '.' . $extension;
        }

        // Batas panjang nama (optional), bisa di-handle kalau perlu
        if (strlen($finalName) > 100) {
            $nameWithoutExt = pathinfo($finalName, PATHINFO_FILENAME);
            $finalName = substr($nameWithoutExt, 0, 80) . '.' . $extension;
        }

        // Simpan ke storage
        $fullPath = $path . '/' . $finalName;
        Storage::disk($disk)->put($fullPath, $contents);

        return $fullPath;
    }

    public static function detectUserAgent($userAgent)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        return [
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'is_mobile' => $agent->isMobile(),
            'is_desktop' => $agent->isDesktop(),
            'is_tablet' => $agent->isTablet(),
        ];
    }
}
