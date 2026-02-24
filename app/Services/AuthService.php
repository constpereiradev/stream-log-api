<?php

namespace App\Services;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService
{
    public function authenticateAndGenerateToken(array $credentials): string
    {
        if (!$this->authenticate($credentials)) {
            throw AuthException::invalidCredentials();
        }

        return $this->generateLoginToken();
    }

    public function authenticate(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    public function generateLoginToken(?string $name = 'login_token', ?array $abilities = ["*"]): string
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw AuthException::authFailed();
        }

        $user->tokens()->where('name', $name)->delete();

        return $user->createToken($name, $abilities)->plainTextToken;
    }

    public function generateIntegrationToken(?string $name = 'integration_token'): string
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw AuthException::authFailed();
        }

        $user->tokens()->where('name', $name)->delete();
        $token = $user->createToken($name, ['api:*']);

        $token->accessToken->update([
            'expires_at' => now()->addYear()
        ]);

        return $token->plainTextToken;
    }

    public function logout($user): bool
    {
        if (!$user) {
            throw AuthException::invalidToken();
        }

        $token = $user->currentAccessToken();

        if ($token && method_exists($token, 'delete')) {
            return $token->delete();
        }

        // Se não houver token mas o usuário possui sessão (Caso de Web)
        // deleta todos os tokens dele
        return $user->tokens()->delete();
    }
}
