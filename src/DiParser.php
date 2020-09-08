<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

final class DiParser
{

    /** @var callable */
    private $creator;

    /** @var CallbackHelper */
    private $helper;

    public function __construct(callable $creator, CallbackHelper $helper)
    {
        $this->creator = $creator;
        $this->helper = $helper;
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
            $msg = 'Access to non-public constructor of class ' . $name;
            throw new ContainerException($msg);
        }

        $tParams = [];
        $params = $method->getParameters();
        foreach ($params as $arg) {
            $varName = $arg->getName();
            $type = $arg->getType();

            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();
                $tParams[$varName] = $this->processNameType($arg, $typeName, $subs, $passedValues);
                continue;
            }

            $tParams[$varName] = $this->scalarValue($arg, $passedValues);
        }

        return $tParams;
    }

    /**
     * @param array<string,string> $subs
     * @param array<mixed> $passedValues
     *
     * @return mixed
     */
    private function processNameType(ReflectionParameter $param, string $typeName, array $subs, array &$passedValues)
    {
        if (interface_exists($typeName)) {
            return $this->interfaceValue($param, $typeName, $subs, $passedValues);
        }

        if (class_exists($typeName)) {
            return $this->classValue($param, $typeName, $passedValues);
        }

        return $this->scalarValue($param, $passedValues);
    }

    /**
     * @param array<string,string> $subs
     * @param array<mixed> $values
     *
     * @return void|null|object
     */
    private function interfaceValue(ReflectionParameter $param, string $name, array $subs, array &$values)
    {
        if ($obj = $this->getObjectValue($name, $values)) {
            return $obj;
        }

        if (array_key_exists($name, $subs)) {
            return call_user_func_array($this->creator, [$subs[$name]]);
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull()) {
            return null;
        }

        try {
            return call_user_func_array($this->creator, [$name]);
        } catch (NotFoundException $exc) {
            $msg = sprintf(
                'Missing required substitutions %s passed to %s::%s()',
                $name,
                $param->getDeclaringClass()->getName(), // @phpstan-ignore-line
                $param->getDeclaringFunction()->getName()
            );
            throw new ContainerException($msg, $exc->getCode(), $exc);
        }
    }

    /**
     * @param array<mixed> $values
     *
     * @return void|null|object
     */
    private function classValue(ReflectionParameter $param, string $className, array &$values)
    {
        if ($obj = $this->getObjectValue($className, $values)) {
            return $obj;
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull()) {
            return null;
        }

        return call_user_func_array($this->creator, [$className]);
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function scalarValue(ReflectionParameter $param, array &$values)
    {
        // @todo: Validate scalar type
        if (array_key_exists(0, $values)) {
            return $this->scalarFromValue($values);
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull()) {
            return null;
        }

        if (!$param->isOptional()) {
            $msg = sprintf(
                'Missing required argument $%s passed to %s::%s()',
                $param->getName(),
                $param->getDeclaringClass()->getName(), // @phpstan-ignore-line
                $param->getDeclaringFunction()->getName()
            );
            throw new ContainerException($msg);
        }
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function scalarFromValue(array &$values)
    {
        if (!is_array($values[0])) {
            return array_shift($values);
        }

        if (!isset($values[0][0])) {
            return array_shift($values);
        }

        if ($values[0][0] !== 'CALL::OBJECT' && $values[0][0] !== 'CALL::SCALAR') {
            return array_shift($values);
        }

        if ($values[0][0] === 'CALL::OBJECT') {
            throw new ContainerException('Require CALL::SCALAR, CALL::OBJECT given');
        }

        return $this->doCall(array_shift($values));
    }

    /**
     * @param array<mixed> $values
     *
     * @return object|null
     */
    private function getObjectValue(string $className, array &$values)
    {
        if (!isset($values[0])) {
            return null;
        }

        if (is_object($values[0]) && ($values[0] instanceof $className)) {
            return array_shift($values);
        }

        if (!is_array($values[0])) {
            return null;
        }

        if (!isset($values[0][0])) {
            return null;
        }

        if ($values[0][0] !== 'CALL::OBJECT' && $values[0][0] !== 'CALL::SCALAR') {
            return null;
        }

        if ($values[0][0] === 'CALL::SCALAR') {
            throw new ContainerException('Require CALL::OBJECT, CALL::SCALAR given');
        }

        return $this->doCall(array_shift($values));
    }

    /**
     * @param array{string,string,array<mixed>} $values
     *
     * @return  mixed
     */
    private function doCall(array $values)
    {
        array_shift($values);
        $callback = array_shift($values);
        $args = array_shift($values);

        $callback = $this->helper->toCallback($callback);
        return call_user_func_array($callback, (array)$args);
    }
}
