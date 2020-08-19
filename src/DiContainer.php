<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use ReflectionClass;
use ReflectionException;

final class DiContainer extends AbstractDiContainer
{

    protected function createObject(DiRule $rule) : Object
    {
        $ref = new ReflectionClass($rule->getClassname());
        if ($ref->isAbstract()) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->getClassname());
        }

        $params = $this->parser->parse($ref->getConstructor(), $rule->getParams(), $rule->getSubstitutions());
        $object = $this->getObject($ref, $params);

        return $this->doCall($ref, $object, $rule->call());
    }

    /**
     * @param ReflectionClass<Object> $ref
     * @param array<mixed> $params
     */
    private function getObject(ReflectionClass $ref, array $params) : object
    {
        try {
            return $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }

    /**
     * @param ReflectionClass<Object> $ref
     * @param array<array<mixed>> $calls
     */
    private function doCall(ReflectionClass $ref, object $retObj, array $calls) : object
    {
        foreach ($calls as $args) {
            $fn = array_shift($args);
            $type = null;
            if (count($args) === 2) {
                $type = array_pop($args);
            }
            $args = array_shift($args);

            $method = $ref->getMethod($fn);
            $params = $this->parser->parse($method, $args, []);
            if ($type === '.:CHAIN:.') {
                $retObj = $method->invokeArgs($retObj, $params);
            }
        }

        return $retObj;
    }
}
