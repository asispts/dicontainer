<?php declare(strict_types=1);

namespace Xynha\Tests\Data;

class ScalarRequired
{
    public $bool;
    public $string;
    public $int;
    public $float;
    public $boolArray;
    public $emptyArray;
    public $stringArray;
    public $intArray;
    public $floatArray;

    public function __construct(
        bool $bool,
        string $string,
        int $int,
        float $float,
        array $emptyArray,
        array $boolArray,
        array $stringArray,
        array $intArray,
        array $floatArray
    ) {
        $this->bool = $bool;
        $this->string = $string;
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->emptyArray = $emptyArray;
        $this->boolArray = $boolArray;
        $this->stringArray = $stringArray;
        $this->intArray = $intArray;
        $this->floatArray = $floatArray;
    }
}

class MixedArgument{
    public $arg;
    public function __construct($arg)
    {
        $this->arg = $arg;
    }
}
