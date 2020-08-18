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

class InterfaceAFactory
{
    public $obj;
    public function getInstance()
    {
        if (!$this->obj) {
            $this->obj = new ImplementInterfaceA();
        }
        return $this->obj;
    }
}
