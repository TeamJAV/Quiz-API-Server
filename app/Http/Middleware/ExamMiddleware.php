<?php

namespace App\Http\Middleware;

use App\Models\ResultDetail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->header('rd_id')) {
            return response()->json([
                'status' => 403,
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        $result_detail_id = $request->header('rd_id');
        $result_detail = ResultDetail::find($result_detail_id);
        if (!$result_detail) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Not found student'
            ], 404);
        }
        if (Carbon::now()->gt(Carbon::parse($result_detail->time_end)) && $result_detail->is_finished == 0) {
            return response()->json([
                'status' => 412,
                'success' => false,
                'message' => 'End time for ' . $result_detail->student_name
            ], 412);
        }
        if ($result_detail->is_finished == 1) {
            return response()->json([
                'status' => 412,
                'success' => false,
                'message' => $result_detail->student_name . ' is finished the exam'
            ], 412);
        }
        return $next($request);
    }
}
