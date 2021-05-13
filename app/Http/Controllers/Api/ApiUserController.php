<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
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
        return self::responseJSON(200, true, 'User profile', $user);
    }

    public function update(UpdateUserRequest $request)
    {
        $this->userRepository->update(auth()-id(), ['password' => Hash::make($request->get('n_password'))]);
        return self::responseJSON(200, true, 'Update success', new UserCollection(auth()->user()));
    }
}
