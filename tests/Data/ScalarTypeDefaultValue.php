<?php

namespace Xynha\Tests\Data;

class ScalarTypeDefaultValue
{
    public $string;
    public $int;
    public $float;
    public $emptyArray;
    public $stringArray;
    public $intArray;
    public $floatArray;

    public function __construct(
        string $string = 'Default value',
        int $int = 6,
        float $float = 3.14,
        array $empty = [],
        array $stringArray = ['default', 'value'],
        array $intArray = [6,11,7],
        array $floatArray = [3.14,3.8]
    ) {
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->emptyArray = $empty;
        $this->stringArray = $stringArray;
        $this->intArray = $intArray;
        $this->floatArray = $floatArray;
    }
}
