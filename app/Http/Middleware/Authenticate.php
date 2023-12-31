<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!auth()->user()) {
            return abort(401);
        }
        return $request->expectsJson() ? null : abort(401);
    }
}
