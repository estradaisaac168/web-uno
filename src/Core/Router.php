<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Container;


class Router{

    private array $routes = [];


    public function get(string $uri, array $action): Route{
        return $this->add('GET', $uri, $action);
    }


    public function post(string $uri, array $action) : Route{
        return $this->add('POST', $uri, $action);
    }


    public function add(
        string $method,
        string $uri,
        array $action
    ): Route{ //Este metodo me retorna una Route
        $route = new Route( //Instancio la clase Route y le paso por parametro los valores que recibo en el metodo.
            $method, 
            $this->compile($uri), 
            $action 
        );

        $this->routes[] = $route;

        return $route;
    }

    public function compile(string $uri): string{
        return '#^' . preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $uri) . '$#';
    }


    //$route->method es la variable que le paso al objeto,
    //$request->method = GET || POST
    public function dispatch(Request $request, Container $container): void {
        foreach($this->routes as $route){
            
            if($route->method !== $request->method()){ 
                continue;
            }

            if(!preg_match($route->pattern, $request->uri(), $matches)){ //compare and save result into $matches
                continue; 
            }


            /**
             * Esta funcion filtra $matches y se queda solo con las claves que son strings, descartando las demas claves
             * 
             * array: //Aca $matches ya trae data en forma de array
             * callback: //Recibe la clave y no el valor en donde $k es la clave del array
             * mode: ARRAY_FILTER_USE_KEY, literalmente la clave
             */
            $params = array_filter(
                $matches, 
                fn($k) => is_string($k), 
                ARRAY_FILTER_USE_KEY
            );

            foreach($route->middleware as $middleware){
                $container->resolve($middleware)->handler($request);
            }


            [$controller, $method] = $route->action; //destructuring = $var1 = Controller, $var2 = method
            $instance = $container->resolve($controller);

            if(!method_exists($instance, $method)){
                throw new \Exception("Method {$method} no found");
            }
                

            /**
             * //Esta funcion llama dinamicamente a un metodo de un objeto, pasando como parametros un array.
             *
             * [$instance, $method] => $instance->$method(...);
             */
            call_user_func_array([$instance, $method], $params); 
            return;
            // $instance->method(array_values(...$params));
        }

        throw new \Exception("Route No found", 404);
        
    }

}