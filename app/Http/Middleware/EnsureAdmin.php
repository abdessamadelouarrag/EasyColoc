<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
        return $next($request);
    }
}
