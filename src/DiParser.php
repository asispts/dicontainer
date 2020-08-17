<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class DiParser
{

    /** @var callable */
    private $creator;

    public function __construct(callable $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @param array<mixed> $passedValues
     * @param array<string,string> $subs
     *
     * @return array<mixed>
     */
    public function parse(?ReflectionMethod $method, array $passedValues, array $subs) : array
    {
        if ($method === null) {
            return [];
        }

        if ($method->isPrivate() || $method->isProtected()) {
            $name = $method->getDeclaringClass()->getName();
            $msg = 'Access to non-public method of class ' . $name;
            if ($method->isConstructor()) {
                $msg = 'Access to non-public constructor of class ' . $name;
            }
            throw new ContainerException($msg);
        }

        $tParams = [];
        $params = $method->getParameters();
        foreach ($params as $arg) {
            $varName = $arg->getName();
            if (is_object($arg->getClass())) {
                $varValue = $this->getObjectArg($arg, $arg->getClass(), $subs);
                $tParams[$varName] = $varValue;
                continue;
            }

            $varValue = $this->getNonObjectValue($arg, $passedValues);
            $tParams[$varName] = $varValue;
        }

        return $tParams;
    }

    /**
     * @param ReflectionClass<Object> $objArg
     * @param array<string,string> $subs
     */
    private function getObjectArg(ReflectionParameter $arg, ReflectionClass $objArg, array $subs) : ?object
    {
        $className = $objArg->getName();
        if ($objArg->isInterface() && !$arg->isOptional() && !array_key_exists($className, $subs)) {
            throw new ContainerException('Missing interface ' . $className . ' substitution');
        }

        if (array_key_exists($className, $subs)) {
            $className = $subs[$className];
        }

        /** @var class-string|object $className */
        if (is_object($className)) {
            return $className;
        }

        list($hasValue, $argValue) = $this->getDefaultValue($arg);
        if ($hasValue) {
            return $argValue;
        }

        return call_user_func_array($this->creator, [$className]);
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function getNonObjectValue(ReflectionParameter $arg, array &$values)
    {
        // @todo: Validate scalar type
        if (count($values) > 0) {
            $key = key($values);
            $value = $values[$key];
            unset($values[$key]);
            return $value;
        }

        list($hasValue, $argValue) = $this->getDefaultValue($arg);
        if ($hasValue) {
            return $argValue;
        }

        throw new ContainerException('Missing required value for $' . $arg->getName());
    }

    /** @return array{bool,mixed} */
    private function getDefaultValue(ReflectionParameter $arg) : array
    {
        if ($arg->isDefaultValueAvailable()) {
            return [true, $arg->getDefaultValue()];
        }

        if ($arg->allowsNull()) {
            return [true, null];
        }

        return [false, null];
    }
}
