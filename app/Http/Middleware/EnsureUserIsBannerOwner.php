<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsBannerOwner
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->attributes->get('user');

        if (!$user || $user->role !== 'banner_owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
