<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route-level check: only organization administrators may pass.
 * NOTE: this checks the user's ROLE only. It says nothing about which
 * organization's data the request is trying to touch.
 */
class EnsureOrgAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isOrgAdmin(), 403);

        return $next($request);
    }
}
