<?php

use App\Interfaces\IMiddleware;
use App\Core\Http\Request;
use Closure;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // validación
        return $next($request);
    }
}
