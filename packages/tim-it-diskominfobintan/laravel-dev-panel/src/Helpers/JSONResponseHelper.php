<?php

namespace TimItDiskominfoBintan\DevPanel\Helpers;

class JsonResponseHelper
{
    // pakai ini untuk default message
    public static $SUCCESS_INDEX = "Data berhasil ditampilkan.";
    public static $SUCCESS_STORE = "Data berhasil disimpan.";
    public static $SUCCESS_SHOW = "Detail data berhasil ditampilkan";
    public static $SUCCESS_UPDATE = "Data berhasil diubah.";
    public static $SUCCESS_DELETE = "Data telah dihapus.";
  
    public static $FAILED_INDEX = "Gagal menampilkan data.";
    public static $FAILED_STORE = "Gagal menyimpan data.";
    public static $FAILED_SHOW = "Gagal menampilkan detail data.";
    public static $FAILED_UPDATE = "Gagal mengubah data.";
    public static $FAILED_DELETE = "Gagal menghapus data.";
    public static $FAILED_VALIDATE = "Data yang diberikan tidak valid.";

    // cara menggunakan -> JsonResponseHelper::success($data, JsonResponseHelper::$SUCCESS_STORE);
    public static function success($data = null, $message = 'Success', $code = 200, $headers = [])
    {
        $response = [
            'status' => 'success',
            'success' => true,
            'message' => $message
        ];
        
        if ($message != self::$SUCCESS_DELETE) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $code, $headers);
    }

    // cara menggunakan -> JsonResponseHelper::successWithAdditional($data['data'], ['meta' => $data['meta']], "Login berhasil.");
    public static function successWithAdditional($data = null, $optional, $message = 'Success', $code = 200, $headers = [])
    {
        $response = [
            'status' => 'success',
            'success' => true,
            'message' => $message,
            ...$optional
        ];
        
        if ($message != self::$SUCCESS_DELETE) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $code, $headers);
    }

    // cara menggunakan -> JsonResponseHelper::error($e->getMessage(), JsonResponseHelper::$FAILED_UPDATE . " " . $e->getMessage());
    public static function error($error = null, $message = 'Something went wrong', $code = 500, $headers = [])
    {
        $response = [
            'success' => false,
            'status' => 'error',
            'message' => $message,
        ];

        if (config('app.debug') && !is_array($error)) {
            $response['error'] = $error;
        }

        if ($code == 422) {
            $response['errors'] = $error;
        }

        return response()->json($response, $code, $headers);
    }
}
