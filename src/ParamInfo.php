<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionParameter;

final class ParamInfo
{

    /** @var string */
    private $name;

    /** @var string */
    private $className;

    /** @var bool */
    private $isObject = false;

    /** @var bool */
    private $hasDefault = false;

    /** @var bool */
    private $defValue;

    /** @var bool */
    private $allowsNull = false;

    public function __construct(ReflectionParameter $param)
    {
        $this->name = $param->getName();
        $this->fetchInfo($param);
    }

    public function name() : string
    {
        return $this->name;
    }

    public function isObject() : bool
    {
        return $this->isObject;
    }

    public function className() : string
    {
        return $this->className;
    }

    public function hasDefaultValue() : bool
    {
        return $this->hasDefault;
    }

    /** @return mixed */
    public function defaultValue()
    {
        return $this->defValue;
    }

    public function allowsNull() : bool
    {
        return $this->allowsNull;
    }

    private function fetchInfo(ReflectionParameter $param) : void
    {
        if (is_object($param->getClass())) {
            $this->isObject = true;
            $this->className = $param->getClass()->getName();
        }

        if ($param->isDefaultValueAvailable()) {
            $this->hasDefault = true;
            $this->defValue = $param->getDefaultValue();
        }

        $this->allowsNull = $param->allowsNull();
    }
}
