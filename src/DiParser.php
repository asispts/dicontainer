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
                $varValue = $this->getObjectArg($arg, $arg->getClass(), $subs, $passedValues);
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
     * @param array<mixed> $values
     */
    private function getObjectArg(
        ReflectionParameter $arg,
        ReflectionClass $objArg,
        array $subs,
        array &$values
    ) : ?object {
        $className = $objArg->getName();
        if ($objArg->isInterface() && !$arg->isOptional()) {
            $className = $this->getSubstitution($className, $subs);
        }

        /** @var class-string|object $className */
        if (is_object($className)) {
            return $className;
        }

        if ($argValue = $this->getObjectFromValue($values)) {
            return $argValue;
        }

        list($hasValue, $argValue) = $this->getDefaultValue($arg);
        if ($hasValue) {
            return $argValue;
        }

        return call_user_func_array($this->creator, [$className]);
    }

    /** @param array<mixed> $values */
    private function getObjectFromValue(array &$values) : ?object
    {
        if (count($values) <= 0) {
            return null;
        }

        if (is_object($values[0])) {
            return array_shift($values);
        }

        if (key((array)$values[0]) !== '.:INSTANCE:.') {
            return null;
        }

        $data = array_shift($values);
        if (!is_array($data['.:INSTANCE:.'])) {
            if (is_object($data['.:INSTANCE:.'])) {
                return $data['.:INSTANCE:.'];
            }

            return call_user_func_array($this->creator, [$data['.:INSTANCE:.']]);
        }

        list($class, $fn) = $data['.:INSTANCE:.'];
        $object = call_user_func_array($this->creator, [$class]);
        /** @var callable $callable */
        $callable = [$object, $fn];

        return call_user_func_array($callable, []);
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

        if (!$arg->isOptional()) {
            $msg = sprintf(
                'Missing required argument $%s passed to %s::%s()',
                $arg->getName(),
                $arg->getDeclaringClass()->getName(), // @phpstan-ignore-line
                $arg->getDeclaringFunction()->getName()  // @phpstan-ignore-line
            );
            throw new ContainerException($msg);
        }
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

    /**
     * @param array<string,string> $subs
     *
     * @return string|object
     */
    private function getSubstitution(string $key, array $subs)
    {
        if (array_key_exists($key, $subs)) {
            return $subs[$key];
        }

        return call_user_func_array($this->creator, [$key]);
    }
}
