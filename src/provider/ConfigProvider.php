<?php

namespace mindplay\foobox\provider;

use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function __construct(
        /**
         * @param array<string,mixed>
         */
        private array $values
    ) {}

    public function getFactories(): array
    {
        $factories = [];
        
        foreach ($this->values as $key => $value) {
            $factories[$key] = fn() => $value;
        }

        return $factories;
    }

    public function getExtensions(): array
    {
        return [];
    }
}
