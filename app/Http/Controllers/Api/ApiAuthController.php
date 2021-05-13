<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\UserCollection;
use App\Repositories\User\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ApiAuthController extends ApiBaseController
{
    //
    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function signup(SignupRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->only(['password', 'name', 'email']);
        try {
            $input['password'] = bcrypt($input['password']);
            $user = $this->user_repository->create($input);
            $user->sendEmailVerificationNotification();
            $token = $user->createToken('Personal Token');
            return self::responseJSON(200, true, 'Signup success', [
                'user' => new UserCollection($user),
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ])->cookie('_token', $token->accessToken, Carbon::now()->diffInMinutes(Carbon::parse($token->token->expires_at)),
                '/', $request->getHttpHost(), true, true);
        } catch (\Exception $exception) {
            return self::responseJSON(500, false, $exception->getMessage());
        }
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return self::responseJSON(401, false, 'Unauthorized');
        }
        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();
            return self::responseJSON(401, false, 'Your email address is not verified. Please check your email');
        }
        $token = $request->user()->createToken('Personal Token');
        $access_token = $token->accessToken;
        if ($request->filled('remember_me')) {
            $token->token->expires_at = Carbon::now()->addYears(1);
            $token->save();
        }
        $context = [
            'user' => new UserCollection($request->user()),
            'token' => [
            'access_token' => $access_token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ]
        ];
        return self::responseJSON(200, true, 'Login success', $context)
            ->cookie('_token', $access_token, Carbon::now()->diffInMinutes(Carbon::parse($token->token->expires_at)),
            '/', $request->getHttpHost(), true, true);

    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->user()) {
                $request->user()->token()->revoke();
                return self::responseJSON(200, true, 'Logout success');
            }
            return self::responseJSON(400, false, 'User are not logged in');
        } catch (\Exception $exception) {
            return self::responseJSON(500, false, $exception->getMessage());
        }
    }

    public function forgot(ForgotPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user_repository->getByEmail($request->get('email'));
        if (!$user) {
            return self::responseJSON(400, false, 'Don\'t have email');
        }
        Password::sendResetLink($request->only('email'));
        return self::responseJSON(200, true, 'Reset password link sent to your email');
    }
}
