<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
namespace Xynha\Tests\Data;

class Injector
{
    public function getClass(string $value) : MixedArgument
    {
        return new MixedArgument('Injector::getClass => ' . $value);
    }

    public function getArray() : array
    {
        return ['Injector', 'getArray'];
    }
}


class ClassInjected
{
    public $obj;
    public function __construct(MixedArgument $obj){
        $this->obj = $obj;
    }
}

class ArrayInjected
{
    public $values;
    public function __construct(array $values){
        $this->values = $values;
    }
}
