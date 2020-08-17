<?php

namespace Xynha\Tests\Data;


class ScalarType
{
    public $string;
    public $int;
    public $float;
    public $emptyArray;
    public $stringArray;
    public $intArray;
    public $floatArray;

    public function __construct(
        string $stringVal,
        int $intVal,
        float $floatVal,
        array $emptyVal,
        array $stringArrayVal,
        array $intArrayVal,
        array $floatArrayVal
    ) {
        $this->string = $stringVal;
        $this->int = $intVal;
        $this->float = $floatVal;
        $this->emptyArray = $emptyVal;
        $this->stringArray = $stringArrayVal;
        $this->intArray = $intArrayVal;
        $this->floatArray = $floatArrayVal;
    }
}
