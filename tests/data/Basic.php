<?php

class NoConstructor
{

}

class A
{

    public $b;

    public function __construct(B $b)
    {
        $this->b = $b;
    }
}

class B
{

    public $c;

    public function __construct(C $c)
    {
        $this->c = $c;
    }
}

class C
{

    public $d;

    public $e;

    public function __construct(D $d, E $e)
    {
        $this->d = $d;
        $this->e = $e;
    }
}


class D
{

}

class E
{

    public $f;

    public function __construct(F $f)
    {
        $this->f = $f;
    }
}

class F
{

}

class GenericDefaultValue
{

    public $std;

    public $a;

    public $b;

    public $i;

    public $f;

    public function __construct(
        stdClass $std = null,
        string $a = 'Default',
        array $b = ['Default'],
        int $i = 6,
        float $f = 3.14
    ) {
        $this->std = $std;
        $this->a = $a;
        $this->b = $b;
        $this->i = $i;
        $this->f = $f;
    }
}

class SharedInstance
{

    public $arg;

    public function __construct($arg = 'Default value')
    {
        $this->arg = $arg;
    }

    public function setArg($arg)
    {
        $this->arg = $arg;
    }
}
