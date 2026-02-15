<?php

namespace App\Helpers;

use illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function jsonResponse($success, $message, $data, $statusCode): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
// fungsi file ini adalah agar struktur response api nya tetap sama
