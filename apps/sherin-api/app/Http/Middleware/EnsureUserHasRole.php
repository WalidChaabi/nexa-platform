<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  string  ...$roles  Role labels: admin, owner, employer
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $allowed = array_map(
            fn (string $role) => UserRole::from(match ($role) {
                'admin' => 1,
                'owner' => 2,
                'employer' => 3,
                default => throw new \InvalidArgumentException("Unknown role: {$role}"),
            }),
            $roles
        );

        if (! in_array($user->role(), $allowed, true)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
