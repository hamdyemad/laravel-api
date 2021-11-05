<?php

namespace App\Http\Middleware;

use App\Traits\Res;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddlware
{
    use Res;
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
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->sendRes('Token is Invalid',false);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->sendRes('Token is Expired',false);
            }else{
                return $this->sendRes('Authorization Token not found',false);
            }
        }
        return $next($request);
    }
}
