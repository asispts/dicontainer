<?php

namespace Xynha\Tests\Data;

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
