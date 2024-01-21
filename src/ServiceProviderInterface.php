<?php

namespace mindplay\foobox;

use Psr\Container\ContainerInterface;

interface ServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function getServiceKeys(): array;

    public function createService(string $id, ContainerInterface $container): mixed;

    /**
     * @return string[]
     */
    public function getExtensionKeys(): array;

    public function extendService(string $id, ContainerInterface $container, mixed $previous): mixed;
}
