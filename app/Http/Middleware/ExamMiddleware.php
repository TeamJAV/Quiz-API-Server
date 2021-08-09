<?php

namespace App\Http\Middleware;

use App\Models\ResultDetail;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
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
                'status' => 401,
                'success' => false,
                'message' => 'Must have name student'
            ]);
        }
        try {
            $result_detail_id = decrypt($request->header('rd_id'));
            $result_detail = ResultDetail::find($result_detail_id);
            if (Carbon::now()->second(0)->gt(Carbon::parse($result_detail->time_end)) && $result_detail->is_finished == 0){
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'End time for ' . $result_detail->student_name
                ], 400);
            }
            if ($result_detail->is_finished == 1) {
                return response()->json([
                    'status' => 422,
                    'success' => false,
                    'message' => $result_detail->student_name . ' is finished the exam'
                ], 400);
            }
            return $next($request);
        } catch (DecryptException $exception) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Wrong auth result'
            ]);
        }
    }
}
