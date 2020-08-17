<?php

namespace Xynha\Tests\Data;

class DependInterfaceA
{
    public $arg;
    public function __construct(InterfaceA $arg)
    {
        $this->arg = $arg;
    }
}
