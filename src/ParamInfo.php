<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class ParamInfo
{

    /** @var string */
    private $name;

    /** @var string */
    private $className;

    /** @var bool */
    private $isObject = false;

    /** @var bool */
    private $hasValue = false;

    /** @var mixed */
    private $value;

    /** @param array<int,mixed> $values */
    public function __construct(ReflectionParameter $param, array &$values)
    {
        $this->name = $param->getName();
        $this->fetchInfo($param, $values);
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

    public function hasValue() : bool
    {
        return $this->hasValue;
    }

    /** @return mixed */
    public function getValue()
    {
        return $this->value;
    }

    /** @param array<int,mixed> $values */
    private function fetchInfo(ReflectionParameter $param, array &$values) : void
    {
        if (is_object($param->getClass())) {
            $this->getObjectValue($param, $values);
            return;
        }

        if (count($values) <= 0) {
            $this->getDefaultValue($param);
            return;
        }

        // @todo: Validate scalar type
        // Get from passed values
        $key = key($values);
        $value = $values[$key];
        unset($values[$key]);
        $this->setValue($value);
    }

    private function getObjectValue(ReflectionParameter $param, array &$values) : void
    {
        try {
            $this->isObject = true;
            $this->className = $param->getClass()->getName();
            $this->getDefaultValue($param);
        } catch (ContainerException $exc) {
        }
        // @todo: get from passed values
    }

    private function getDefaultValue(ReflectionParameter $param) : void
    {
        if ($param->isDefaultValueAvailable()) {
            $this->setValue($param->getDefaultValue());
            return;
        }

        if ($param->allowsNull()) {
            $this->setValue(null);
            return;
        }

        throw new ContainerException('Missing required value for $' . $this->name);
    }

    /** @param mixed $value */
    private function setValue($value) : void
    {
        $this->hasValue = true;
        $this->value = $value;
    }
}
