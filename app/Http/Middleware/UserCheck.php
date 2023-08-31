<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenName = auth()->user()->currentAccessToken()->name;

        if ($tokenName == "posUserToken") {
            return $next($request);
        } else {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
            ]);
        }
        return $next($request);
    }
}
