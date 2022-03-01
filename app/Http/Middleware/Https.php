<?php

namespace App\Http\Middleware;

use Closure;

class Https
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
        dd($request->server->get("HTTP_X_FORWARDED_PROTO"));
        if(!($request->server->get("HTTP_X_FORWARDED_PROTO")) && env("APP_ENV") == "production"){
            echo "middlewere middlewere";
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
