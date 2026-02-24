<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
    
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly LogService $logService
    ) {}

    public function authenticate(AuthRequest $authRequest): JsonResponse
    {
        $request = $authRequest->validated();

        $token = $this->authService->authenticateAndGenerateToken($request);

        return response()->json([
            'user' => Auth::user(),
            'token' => $token,
            'type' => 'Bearer'
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!$request->user()) {
            throw AuthException::userNotFound();
        }

        try {
            if ($this->authService->logout($request->user())) {
                return $this->success([]);
            }

            throw AuthException::logoutFailed();
        } catch (AuthException $e) {
            $this->logService->logError('Logout failed: ' . $e->getMessage(), ['exception' => $e]);

            throw $e;
        } catch (\Exception $e) {
            $this->logService->logError('Logout failed: ' . $e->getMessage(), ['exception' => $e]);
            throw AuthException::logoutFailed();
        }
    }
}
