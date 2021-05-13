<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use App\User;
use Illuminate\Http\Request;

class ApiVerificationController extends ApiBaseController
{
    //
    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function verify(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (!$request->hasValidSignature()) {
            return self::responseJSON(401, false, 'Invalid/Expired email url provided.');
        }
        $user = $this->user_repository->find($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return self::responseJSON(200, true, 'Verify success email.');
    }

    public function resend(): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return self::responseJSON(400, false, "Email already verified.");
        }
        auth()->user()->sendEmailVerificationNotification();
        return self::responseJSON(200, true, "Email verification link sent on your email id.");
    }
}
