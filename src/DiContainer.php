<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use ReflectionClass;

final class DiContainer extends AbstractDiContainer
{

    protected function createObject(DiRule $rule) : Object
    {
        $ref = new ReflectionClass($rule->getClassname());
        if ($ref->isAbstract()) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->getClassname());
        }

        $params = $this->parser->parse($ref->getConstructor(), $rule->getParams(), $rule->getSubstitutions());
        return $this->getObject($ref, $params);
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
}
