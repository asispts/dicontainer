<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

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
        $object = $this->getObject($ref, new ClassInfo($ref->getConstructor()));

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
    public function getParams(ClassInfo $info) : array
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
            if ($arg->hasDefaultValue()) {
                return $arg->defaultValue();
            }

            return $this->get($arg->className());
        }

        // Process scalar parameters:
        // 1. Get value from rule
        // 2. Get default value if available
        // 3. Return null if supported
        // 4. Throw required value exception
        if ($arg->hasDefaultValue()) { // #2
            return $arg->defaultValue();
        }

        if ($arg->allowsNull()) { // #3
            return null;
        }

        // #4
        throw new ContainerException(sprintf('Required value for variable $%s', $arg->name()));
    }
}
