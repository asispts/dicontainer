<?php

namespace Xynha\Tests\Data;

interface InterfaceA{}
class ImplementInterfaceA implements InterfaceA{}

class DependInterfaceA
{
    public $arg;
    public function __construct(InterfaceA $arg)
    {
        $this->arg = $arg;
    }
}
