<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttpProtocol
{
    public function handle($request, Closure $next)
    {
        if (\App::environment(['production']) && $_SERVER["HTTP_X_FORWARDED_PROTO"] != 'https') {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
