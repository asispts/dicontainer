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
        $object = $this->getObject($ref, new ClassInfo($ref->getConstructor(), $rule));

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
            if ($arg->isObject()) {
                $this->checkCircular($info->className(), $arg->className());
            }
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

    private function checkCircular(string $className, string $argName) : void
    {
        $ref = new ReflectionClass($argName);
        $info = new ClassInfo($ref->getConstructor(), $this->list->getRule($argName));

        foreach ($info->getParams() as $param) {
            if ($param->isObject() && $className === $param->className()) {
                throw new ContainerException('Cyclic dependencies detected');
            }
        }
    }
}
