<?php

namespace App\Exceptions;

use App\User;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['message' => 'Method not allowed'], 405);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['message' => 'Not found'], 404);
        }
        if ($exception instanceof ServerException) {
            return response()->json(['message' => 'Server error'], 500);
        }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
//        if ($request->user() == null && $request->filled('email')) {
//            $email = $request->get('email');
//            $user = User::query()->where('email', $email)->first();
//            $user->sendEmailVerificationNotification();
//            return response()->json(['status' => 403, 'success' => false, 'message' => 'Please confirm your email to active this account'], 403);
//        }
        return response()->json(['status' => 403, 'success' => false, 'message' => 'Forbidden'], 403);
    }
}
