<?php

namespace App\Http\Middleware;

use Closure;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {

        if (!auth()->guard($guard)->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 413);
        }

        return $next($request);
    }
}
