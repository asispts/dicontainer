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
        if ($ref->isAbstract()) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->getClassname());
        }

        $parser = new DiParser([$this, 'get']);
        $params = $parser->parse($ref->getConstructor(), $rule->getParams(), $rule->getSubstitutions());
        $object = $this->getObject($ref, $params);

        if ($rule->isShared()) {
            $this->instances[$rule->getKey()] = $object;
        }

        return $object;
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
        } catch (ReflectionException $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }
}
