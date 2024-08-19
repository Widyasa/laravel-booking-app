<?php

namespace App\Utils;

class ApiResponse
{
    public static function success($data = null, $proses = null, $module = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $proses.' '.$module.' Success',
        ], $code);
    }

    public static function error($proses = null, $module = null, $errors = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $proses.$module.'failed',
            'errors' => $errors,
        ], $code);
    }
}
