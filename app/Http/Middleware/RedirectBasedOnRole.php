<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin && $request->is('home')) {
                return redirect()->route('admin.dashboard');
            }
            
            if (!Auth::user()->is_admin && $request->is('admin/*')) {
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
