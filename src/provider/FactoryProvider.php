<?php

namespace mindplay\foobox\provider;

use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class FactoryProvider implements ServiceProviderInterface
{
    /**
     * @var array<string,[[string,string],array<string,string>]> map where service ID => [service provider callable, map of parameter names => service IDs]
     */
    private array $services = [];

    public function __construct(array $factoryNames)
    {
        foreach ($factoryNames as $factoryName) {
            $this->services += iterator_to_array(FactoryLoader::load($factoryName));
        }
    }

    public function getFactories(): array
    {
        $factories = [];

        foreach ($this->services as $id => $service) {
            $factories[$id] = function (ContainerInterface $container) use ($service) {
                [[$className, $methodName], $deps] = $service;

                $params = [];

                foreach ($deps as $paramName => $depID) {
                    if ($container->has($depID)) {
                        $params[$paramName] = $container->get($depID);
                    }
                }

                return $className::$methodName(...$params);
            };
        }

        return $factories;
    }

    public function getExtensions(): array
    {
        return []; // ...
    }
}
