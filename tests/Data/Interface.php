<?php

namespace Xynha\Tests\Data;

interface GlobalInterface{}

class GlobalInterfaceImpl implements GlobalInterface{}

class GlobalInterfaceDep
{
    public $obj;
    public function __construct(GlobalInterface $arg)
    {
        $this->obj = $arg;
    }
}


interface SubsInterface{}
class SubsInterfaceImpl implements SubsInterface{}
class OverriddenGlobalSubsImpl extends SubsInterfaceImpl{}
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
