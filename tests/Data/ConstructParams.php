<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
namespace Xynha\Tests\Data;

use stdClass;

class ClassString{
    public $required;
    public $optional;
    public $null;
    public function __construct(string $required, string $optional = 'Optional', ?string $null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ClassBool{
    public function __construct(bool $required, bool $optional = true, ?bool $null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ClassInt extends ClassString{
    public function __construct(int $required, int $optional = 2019, ?int $null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ClassFloat extends ClassString{
    public function __construct(float $required, float $optional = 3.14, ?float $null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ClassArray extends ClassString{
    public function __construct(array $required, array $optional = [3.14], ?array $null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ClassMixed extends ClassString{
    public function __construct($required, $optional = 'Optional', $null = null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ObjectScalar{
    public $obj;
    public $bool;
    public $string;
    public $int;
    public $float;
    public $array;
    public $mixed;
    public function __construct(
        ?stdClass $std,
        ?bool $bool,
        ?string $string,
        ?int $int,
        ?float $float,
        ?array $array,
        $mixed = null
    ) {
        $this->obj = $std;
        $this->bool = $bool;
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->array = $array;
        $this->mixed = $mixed;
    }
}

class MixedArgument{
    public const PUBLIC_CONST = 1;
    private const PRIVATE_CONST = -1;

    public $arg;
    public function __construct($arg)
    {
        $this->arg = $arg;
    }
}
