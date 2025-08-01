<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            Log::info('User activity', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
                'method' => $request->method(),
            ]);
        }

        return $response;
    }
}
