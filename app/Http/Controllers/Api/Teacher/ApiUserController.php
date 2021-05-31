<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends ApiBaseController
{
    //
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = new UserCollection(auth()->user());
        return self::responseJSON(200, true, 'User profile', ['user' => new UserCollection($user)]);
    }

    public function update(UpdateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->userRepository->update(auth() - id(), ['password' => Hash::make($request->get('n_password'))]);
        return self::responseJSON(200, true, 'Update success', ['user' => new UserCollection(auth()->user())]);
    }
}
