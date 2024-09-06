<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {

            if($e instanceof TokenInvalidException){
                return response()->json([
                    'status'  => 'error',
                    'code'    => 401,
                    'message' => 'Token Invalido',
                ],401);
            }

            if($e instanceof TokenExpiredException){
                return response()->json([
                    'status'  => 'error',
                    'code'    => 401,
                    'message' => 'Token Expirado',
                ],401); 
            }
           
            return response()->json([
                'status'  => 'error',
                'code'    => 401,
                'message' => 'Token Extraviado',
            ],401);
        }

        return $next($request);
        
    }
}
