<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function store(StoreUserRequest $storeUserRequest): JsonResponse
    {
        $request = $storeUserRequest->validated();

        $user  = $this->userService->store($request);

        return response()->json([
            'user' => $user
        ]);
    }
}
