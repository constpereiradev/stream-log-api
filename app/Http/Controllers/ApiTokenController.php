<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiTokenController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function generate(): JsonResponse
    {
        $token = $this->authService->generateIntegrationToken();

        return $this->success([
            'token' => $token
        ]);
    }
}
