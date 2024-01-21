<?php

namespace mindplay\foobox\provider;

use mindplay\foobox\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function __construct(
        /**
         * @param array<string,mixed>
         */
        private array $values
    ) {}

    public function getServiceKeys(): array
    {
        return array_keys($this->values);
    }

    public function createService(string $id, ContainerInterface $container): mixed
    {
        return $this->values[$id] ?? null;
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
