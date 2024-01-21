<?php

namespace mindplay\foobox\deps;

class Database
{
    public function __construct(
        public string $dsn
    ) {}

    // ...
}
