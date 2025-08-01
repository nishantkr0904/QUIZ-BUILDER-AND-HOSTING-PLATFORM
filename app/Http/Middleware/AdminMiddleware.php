<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin area.');
        }

        if (!Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'You must have administrator privileges to access this area.');
        }

        return $next($request);
    }
}
