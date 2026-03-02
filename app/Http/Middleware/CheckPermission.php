<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use App\Traits\ApiResponser;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws UnauthorizedException
     */
    public function handle($request, Closure $next, $permissionName): Response {
        if (!auth()->check()) {
            throw new UnauthorizedException();
        }

        $user = auth()->user();
        $hasPermission = method_exists($user, 'hasPermissionTo')
            ? $user->hasPermissionTo($permissionName)
            : (method_exists($user, 'hasPermission') ? $user->hasPermission($permissionName) : false);

        if ($hasPermission) {
            return $next($request);
        }

        throw new UnauthorizedException();
    }
}
