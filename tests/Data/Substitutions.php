<?php declare(strict_types=1);

namespace Xynha\Tests\Data;


interface InterfaceNoRule{}
class ClassNoRule {
    public function __construct(InterfaceNoRule $obj){}
}


interface InterfaceA{}
class ImplementInterfaceA implements InterfaceA {
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}
class DependInterfaceA {
    public $obj;
    public function __construct(InterfaceA $obj)
    {
        $this->obj = $obj;
    }
}


interface InvalidSubsInterface{}
class ImplInvalidSubsInterface implements InvalidSubsInterface{}
class DependInvalidSubsInterface {
    public $obj;
    public function __construct(InvalidSubsInterface $obj)
    {
        $this->obj = $obj;
    }
}
