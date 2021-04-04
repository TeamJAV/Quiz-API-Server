<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected static function responseJSON($status = 200, $success = true, $error = null, $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'success' => $success,
            'errors' => $error,
            'data' => []
        ]);
    }
}
