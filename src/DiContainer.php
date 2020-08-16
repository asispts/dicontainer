<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

final class DiContainer extends AbstractDiContainer
{

    /** @var object[] */
    private $instances;

    protected function createObject(DiRule $rule) : Object
    {
        if (isset($this->instances[$rule->getKey()])) {
            return $this->instances[$rule->getKey()];
        }

        $ref = new ReflectionClass($rule->getClassname());

        $constructor = $ref->getConstructor();
        $params = $constructor ? $this->fetchMethod($constructor) : [];

        try {
            $object = $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        } catch (ReflectionException $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }

        if ($rule->isShared()) {
            $this->instances[$rule->getKey()] = $object;
        }
        return $object;
    }

    /** @return array<mixed> */
    private function fetchMethod(ReflectionMethod $cons) : array
    {
        $args = [];
        $params = $cons->getParameters();

        foreach ($params as $param) {
            $args[$param->getName()] = $this->getParamValue($param);
        }

        return $args;
    }

    /** @return mixed */
    private function getParamValue(ReflectionParameter $param)
    {
        if (is_object($param->getClass())) {
            return $this->getObjectValue($param, $param->getClass());
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }
    }

    /**
     * @param ReflectionClass<Object> $class
     *
     * @return object
     */
    private function getObjectValue(ReflectionParameter $param, ReflectionClass $class)
    {
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        return $this->get($class->getName());
    }
}
