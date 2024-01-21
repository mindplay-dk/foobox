<?php

namespace mindplay\foobox\deps;

class UserRepository
{
    public function __construct(
        public Database $db,
        public Cache $cache
    )
    {}

    public Logger $logger;

    public function setLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }

    // ...
}
