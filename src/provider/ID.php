<?php

namespace mindplay\foobox\provider;

use Attribute;

#[Attribute]
class ID
{
    public function __construct(public string $id)
    {}
}
