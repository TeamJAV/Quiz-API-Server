<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    //
    protected static function response403($message = 'This action is forbidden'): \Illuminate\Http\JsonResponse
    {
        return self::responseJSON(403, false, $message);
    }
}
