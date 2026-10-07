<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->two_factor_confirmed_at) {
            return to_route('security.edit')
                ->with('status', 'Activez la double authentification pour continuer.');
        }

        return $next($request);
    }
}
