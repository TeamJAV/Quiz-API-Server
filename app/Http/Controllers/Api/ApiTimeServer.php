<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiTimeServer extends ApiBaseController
{
    //
    public function index(): \Illuminate\Http\JsonResponse
    {
        $now = Carbon::now();
        return self::responseJSON(200, true, 'Time server', [
            'time_now' => $now->toDateTimeString(),
            'time_zone' => $now->tzName,
            'day' => $now->format('d'),
            'month' => $now->format('m'),
            'year' => $now->format('Y'),
            'hour' => $now->format('h'),
            'min' => $now->format('i'),
            'second' => $now->format('s'),
        ]);
    }
}
