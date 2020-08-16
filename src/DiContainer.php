<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use ReflectionClass;
use ReflectionException;

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
        $object = $this->getObject($ref, new ClassInfo($ref->getConstructor(), $rule->getParams()));

        if ($rule->isShared()) {
            $this->instances[$rule->getKey()] = $object;
        }
        return $object;
    }

    /** @param ReflectionClass<Object> $ref */
    private function getObject(ReflectionClass $ref, ClassInfo $info) : object
    {
        $params = $this->getParams($info);

        try {
            return $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        } catch (ReflectionException $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }

    /** @return array<int,mixed> */
    private function getParams(ClassInfo $info) : array
    {
        $params = [];
        foreach ($info->getParams() as $arg) {
            $params[] = $this->getParamValue($arg);
        }

        return $params;
    }

    /** @return mixed */
    private function getParamValue(ParamInfo $arg)
    {
        if ($arg->isObject()) {
            if ($arg->hasValue()) {
                return $arg->getValue();
            }

            return $this->get($arg->className());
        }

        if ($arg->hasValue()) {
            return $arg->getValue();
        }

        throw new ContainerException('Missing required value for $' . $arg->name());
    }
}
