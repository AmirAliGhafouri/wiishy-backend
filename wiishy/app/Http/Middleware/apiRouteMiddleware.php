<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class apiRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->getHost());
        if($request->getHost() != 'www.wiishy.com')
        {
            return response(['message'=>'Error!'], 400);
        }
        return $next($request);
    }
}
