<?php


namespace App\Core;


class Route
{

    public function __construct(
        public string $method, //POST, GET
        public string $pattern, //Uri
        public array $action,
        public array $middleware = []
    ) {

    }

    public function middleware(string $middleware): self{
        $this->middleware[] = $middleware;
        return $this;
    }
}