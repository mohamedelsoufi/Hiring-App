<?php

namespace App\Http\Middleware;

use App\CustomClass\response;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
class authenticateEmployee
{
    
    public function handle($request, Closure $next)
    {
        try {
            config(['auth.defaults.guard' => 'employee']);
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->toArray();

            if ($payload['type'] != 'Employee') {
                return response::falid('Not authorized', 401);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response::falid('Token is Invalid', 401);

            } else if ($e instanceof TokenExpiredException) {
                return response::falid('Token is Expired', 401);
            } else {
                return response::falid('Authorization Token not found', 401);
            }
        }
        return $next($request);
    }
}
