<?php

namespace App\Services\Auth;

use Laravel\Sanctum\HasApiTokens;

abstract class AbstractAuthService
{
    protected function buildTokenPayload(object $user, string $tokenName): array
    {
        if (! in_array(HasApiTokens::class, class_uses_recursive($user), true)) {
            throw new \RuntimeException('Authenticated model must use HasApiTokens trait.');
        }

        $issuedAt = now();
        $expiration = config('sanctum.expiration');
        $expiresAt = is_numeric($expiration) ? now()->addMinutes((int) $expiration) : null;

        return [
            'id' => (int) ($user->id ?? 0),
            'name' => (string) ($user->name ?? ''),
            'email' => (string) ($user->email ?? ''),
            'token' => $user->createToken($tokenName, ['*'], $expiresAt)->plainTextToken,
            'permissions' => $this->resolvePermissions($user),
            'token_iat' => $issuedAt->toDateTimeString(),
            'token_exp' => $expiresAt?->toDateTimeString(),
        ];
    }

    protected function profilePayload(object $user): array
    {
        return [
            'id' => (int) ($user->id ?? 0),
            'name' => (string) ($user->name ?? ''),
            'email' => (string) ($user->email ?? ''),
            'permissions' => $this->resolvePermissions($user),
        ];
    }

    protected function resolvePermissions(object $user): array
    {
        if (method_exists($user, 'getPermissionNames')) {
            return $user->getPermissionNames()->values()->toArray();
        }

        if (method_exists($user, 'permissions')) {
            return $user->permissions->pluck('name')->values()->toArray();
        }

        return [];
    }

    protected function revokeCurrentToken(object $user): void
    {
        if (method_exists($user, 'currentAccessToken')) {
            $user->currentAccessToken()?->delete();
        }
    }
}
