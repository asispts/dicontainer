<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionClass;
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
    private $hasValue = false;

    /** @var mixed */
    private $value;

    /**
     * @param array<int,mixed> $values
     * @param array<string,string> $subs
     */
    public function __construct(ReflectionParameter $param, array &$values, array $subs)
    {
        $this->name = $param->getName();
        $this->fetchInfo($param, $values, $subs);
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

    /**
     * @param array<int,mixed> $values
     * @param array<string,string> $subs
     */
    private function fetchInfo(ReflectionParameter $param, array &$values, array $subs) : void
    {
        if (is_object($param->getClass())) {
            $this->getObjectValue($param, $param->getClass(), $subs);
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

    /**
     * @param ReflectionClass<Object> $cls
     * @param array<string,string> $subs
     */
    private function getObjectValue(ReflectionParameter $param, ReflectionClass $cls, array $subs) : void
    {
        $this->isObject = true;
        $this->className = $cls->getName();

        if ($cls->isInterface()) {
            if (array_key_exists($this->className, $subs) === false) {
                throw new ContainerException(sprintf('Missing %s substitution', $this->className));
            }

            $this->className = $subs[$this->className];
            return;
        }

        try {
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
