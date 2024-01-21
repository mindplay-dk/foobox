<?php

namespace mindplay\foobox\container;

use mindplay\foobox\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * POC container implementation, only supports services (not extensions) for a single provider.
 */
class Container implements ContainerInterface
{
    /**
     * @var array<string,ServiceProviderInterface>
     */
    private array $providers = [];

    /**
     * @var array<string,mixed>
     */
    private array $instances = [];

    /**
     * @param ServiceProviderInterface[] $providers
     */
    public function __construct(array $providers)
    {
        // NOTE: creating some run-time overhead and duplicating a lot of keys here,
        //       and the container itself is not cacheable, so there is room for
        //       improvement here still...

        foreach ($providers as $provider) {
            $this->providers += array_fill_keys($provider->getServiceKeys(), $provider);
        }
    }

    public function get(string $id): mixed
    {
        if (! isset($this->instances[$id])) {
            $this->instances[$id] = $this->providers[$id]->createService($id, $this);
        }

        return $this->instances[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->providers);
    }
}
