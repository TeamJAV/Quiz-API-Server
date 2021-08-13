<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiVerificationController extends ApiBaseController
{
    //
    private $user_repository;
    const PORT_REACT = "127.0.0.1:3000/login/teacher";

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function verify(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return self::responseJSON(401, false, 'Invalid/Expired email url provided.');
        }
        $user = $this->user_repository->find($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect(self::PORT_REACT);
    }

    public function resend(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return self::responseJSON(403, false, "Where is your token :))");
        }
        if ($user->hasVerifiedEmail()) {
            return self::responseJSON(400, false, "Email already verified.");
        }
        $user->sendEmailVerificationNotification();
        return self::responseJSON(200, true, "Email verification link sent on your email.");
    }
}
