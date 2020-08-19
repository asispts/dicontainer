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

interface FactoryInterface{}
class FactoryInterfaceImpl implements FactoryInterface
{
    public $generated;
    public $passed = 'Initial Value';
    public function __construct(string $generated)
    {
        $this->generated = $generated;
    }
    public function setValue(string $passed)
    {
        $this->passed = $passed;
    }
}
class FactoryInterfaceDep
{
    public $obj;
    public function __construct(FactoryInterface $arg)
    {
        $this->obj = $arg;
    }
}
class FactoryInterfaceFactory
{
    private $obj;
    public function __construct()
    {
        $this->obj = new FactoryInterfaceImpl(uniqid('factory_'));
    }

    public function getInstance(string $passed)
    {
        $this->obj->setValue($passed);
        return $this->obj;
    }
}
