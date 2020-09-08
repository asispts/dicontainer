<?php declare(strict_types=1);

namespace Xynha\Tests\Data;

class Injector
{
    public function getClass(string $value) : MixedArgument
    {
        return new MixedArgument('Injector::getClass => ' . $value);
    }
}


class ClassInjected
{
    public $obj;
    public function __construct(MixedArgument $obj){
        $this->obj = $obj;
    }
}
