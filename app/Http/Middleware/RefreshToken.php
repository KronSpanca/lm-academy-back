<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\AuthenticationExeption;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\middleware\BaseMiddleware;
use Tymon\JWTAuth\Exeptions\TokenExpiredExeption;
use Illuminate\JWTAuth\Exeptions\HttpResponseExeption;



class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       try {
        $this->checkForToken($request);

        if($request->user =JWTauth::parseToken()->authenticate())
        {
             return $next($request);
        }

        throw new AuthenticationExeption('Unauthorized', []);
       } catch(TokenExpiredException $e ){
       throw new HttpResponseExeption(response()->json([
        "message" => 'token expired'
       ], 401));
    } catch(\Exeption $e ){
        throw new AuthenticationExeption('Unauthorized', []);
    
       }
    }
}
