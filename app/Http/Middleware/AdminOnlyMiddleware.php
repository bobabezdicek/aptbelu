<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnlyMiddleware
{
    public function handle(Request $r, Closure $next)
    {
        abort_if(!$r->user() || !$r->user()->is_admin, 403);

        return $next($r);
    }
}
