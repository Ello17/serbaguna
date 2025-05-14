<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('authenticated')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
