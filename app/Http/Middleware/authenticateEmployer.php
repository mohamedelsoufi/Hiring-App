<?php

namespace App\Http\Middleware;

use App\CustomClass\response;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
class authenticateEmployer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            config(['auth.defaults.guard' => 'employer']);
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->toArray();
            if ($payload['type'] != 'Employer') {
                return response::falid('Not authorized', 400);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response::falid('Token is Invalid', 400);
            } else if ($e instanceof TokenExpiredException) {
                return response::falid('Token is Expired', 400);
            } else {
                return response::falid('Authorization Token not found', 400);
            }
        }
        return $next($request);
    }
}
