<?php 

namespace App\Interfaces;

interface IMiddleware{
    public function handle($request, \Closure $next);
}