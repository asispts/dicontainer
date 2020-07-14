<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

/** @internal */
final class DiBuilder
{

    /** @var ContainerInterface */
    private $dic;

    /** @var DiRule */
    private $rule;

    public function __construct(ContainerInterface $dic)
    {
        $this->dic = $dic;
    }

    public function createObject(DiRule $rule) : object
    {
        $this->rule = $rule;
        $ref = new ReflectionClass($rule->getClassname());

        $constructor = $ref->getConstructor();
        $params = $constructor ? $this->fetchMethod($constructor) : [];
        try {
            return $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        } catch (ReflectionException $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
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

    /** @return object */
    private function getObjectValue(ReflectionParameter $param, ReflectionClass $class)
    {
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        return $this->dic->get($class->getName());
    }
}
