<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class user_access
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
        $id=$request->user()->id;
        if($request->userid!=$id){
            return response([
                'status'=>'Error',
                'message'=>'you dont have permission to access'
            ],400);
        }
        return $next($request);
    }
}
