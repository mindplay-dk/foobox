<?php

namespace mindplay\foobox\provider;

use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

/**
 * Collects metadata from factory classes via reflection.
 * 
 * POC only: does not support extensions.
 * 
 * @see \mindplay\foobox\config\AppFactory for an example factory class
 */
abstract class FactoryLoader
{
    /**
     * @return iterable<string,[[string,string],array<string,string>]> map where service ID => [service provider callable, map of parameter names => service IDs]
     */
    public static function load(string $className): iterable
    {
        $class = new ReflectionClass($className);

        foreach ($class->getMethods() as $method) {
            $returnType = $method->getReturnType();

            $id = null;

            if ($returnType instanceof ReflectionNamedType) {
                $id = $returnType->getName();
            }

            if ($idAttr = $method->getAttributes(ID::class)[0] ?? null) {
                $id = $idAttr->newInstance()->id;
            }

            if ($id === null) {
                throw new RuntimeException("missing ID attribute or return-type on factory-method {$className}::{$method->getName()}");
            }

            $deps = [];

            foreach ($method->getParameters() as $param) {
                $type = $param->getType();

                $depId = null;

                if ($type instanceof ReflectionNamedType) {
                    $depId = $type->getName();
                }

                if ($idAttr = $param->getAttributes(ID::class)[0] ?? null) {
                    $depId = $idAttr->newInstance()->id;
                }

                if ($depId === null) {
                    throw new RuntimeException("missing ID attribute or type-hint on parameter \${$param->getName()} of factory-method {$className}::{$method->getName()}");
                }

                $deps[$param->getName()] = $depId;
            }

            yield $id => [
                [$className, $method->getName()],
                $deps
            ];
        }
    }
}
