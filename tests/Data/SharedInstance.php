<?php

namespace Xynha\Tests\Data;

class SharedInstance
{
    public $arg;

    public function __construct($arg = 'Default value')
    {
        $this->arg = $arg;
    }

    public function setArg($arg)
    {
        $this->arg = $arg;
    }
}
