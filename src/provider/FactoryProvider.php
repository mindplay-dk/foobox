<?php

namespace mindplay\foobox\provider;

use mindplay\foobox\ServiceProviderInterface;
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

    public function getServiceKeys(): array
    {
        return array_keys($this->services);
    }

    public function createService(string $id, ContainerInterface $container): mixed
    {
        [[$className, $methodName], $deps] = $this->services[$id];

        $params = [];

        foreach ($deps as $paramName => $depID) {
            if ($container->has($depID)) {
                $params[$paramName] = $container->get($depID);
            }
        }

        return $className::$methodName(...$params);
    }

    public function getExtensionKeys(): array
    {
        return []; // ...
    }

    public function extendService(string $id, ContainerInterface $container, mixed $previous): mixed
    {
        return null; // ...
    }
}
