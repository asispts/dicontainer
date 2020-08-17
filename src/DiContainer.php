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

        foreach ($rule->getCall() as $args) {
            $fn = array_shift($args);
            $callback = count($args) === 2 ? array_pop($args) : null;

            $args = array_shift($args);

            $method = $ref->getMethod($fn);
            $params = $this->parser->parse($method, $args, []);
            $retVal = $method->invokeArgs($object, $params);

            if ($callback === null || !is_callable($callback)) {
                continue;
            }

            call_user_func($callback, $retVal);
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
