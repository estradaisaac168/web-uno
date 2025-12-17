<?php

namespace App\Core;

// use ReflectionClass;
use Exception;

class Container
{
    /**
     * Instancias ya resueltas (singleton por request)
     */
    private array $instances = [];

    /**
     * Bindings: Interface => Implementation
     */
    private array $bindings = [];

    /**
     * Registrar una implementación para una abstracción
     */
    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolver una clase o interfaz
     */
    public function resolve(string $id)
    {
        // 1 ¿Ya existe?
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // 2️ ¿Es una interfaz bindeada?
        if (isset($this->bindings[$id])) {
            $id = $this->bindings[$id];
        }

        // 3️ Reflexión
        $reflection = new \ReflectionClass($id);

        if (!$reflection->isInstantiable()) {
            throw new Exception("No se puede instanciar {$id}");
        }

        $constructor = $reflection->getConstructor();

        // 4️ Sin constructor
        if ($constructor === null) {
            return $this->instances[$id] = new $id;
        }

        // 5️ Resolver dependencias
        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {

            $type = $param->getType();

            if (!$type) {
                throw new Exception(
                    "No se puede resolver \${$param->getName()} en {$id}"
                );
            }

            $dependencies[] = $this->resolve($type->getName());
        }

        // 6️ Crear instancia final
        return $this->instances[$id] = new $id(...$dependencies);
    }
}
