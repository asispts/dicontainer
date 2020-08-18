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
        $retObj = $this->getObject($ref, $params);


        foreach ($rule->getCall() as $args) {
            $fn = array_shift($args);
            $type = null;
            if (count($args) === 2) {
                $type = array_pop($args);
            }
            $args = array_shift($args);

            $method = $ref->getMethod($fn);
            $params = $this->parser->parse($method, $args, []);
            if ($type === null) {
                $method->invokeArgs($retObj, $params);
                continue;
            }

            if ($type === '.:CHAIN:.') {
                $retObj = $method->invokeArgs($retObj, $params);
            }
        }

        return $retObj;
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
