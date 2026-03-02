<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticatedUserType
{
    /**
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        $user = $request->user();
        $provider = config("auth.guards.{$guard}.provider");
        $modelClass = is_string($provider) ? config("auth.providers.{$provider}.model") : null;

        if (! is_object($user) || ! is_string($modelClass) || ! $user instanceof $modelClass) {
            throw new UnauthorizedException('Unauthorized');
        }

        return $next($request);
    }
}
