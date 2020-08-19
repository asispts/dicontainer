<?php

namespace Xynha\Tests\Data;

interface GlobalInterface{}

class GlobalInterfaceImpl implements GlobalInterface
{
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}

class GlobalInterfaceDep
{
    public $obj;
    public function __construct(GlobalInterface $arg)
    {
        $this->obj = $arg;
    }
}


interface SubsInterface{}
class SubsInterfaceImpl implements SubsInterface{
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}
class OverriddenGlobalSubsImpl implements SubsInterface{
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}
class SubsInterfaceDep
{
    public $obj;
    public function __construct(SubsInterface $arg)
    {
        $this->obj = $arg;
    }
}

interface GenericInterface{}
class GenericInterfaceImpl implements GenericInterface{}
class GenericInterfaceDep
{
    public $obj;
    public function __construct(GenericInterface $arg)
    {
        $this->obj = $arg;
    }
}
