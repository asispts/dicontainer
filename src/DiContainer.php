<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

final class DiContainer implements ContainerInterface
{

    /** @var DiRuleList */
    private $rule;

    public function __construct(DiRuleList $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @param class-string|string $id
     */
    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule `%s` is not found or it is an interface', $id));
        }

        return $this->createObject($id);
    }

    /**
     * @param class-string|string $id
     */
    public function has($id)
    {
        return class_exists($id);
    }

    /**
     * @param class-string|string $id
     */
    private function createObject(string $id) : object
    {
        /** @var class-string $classname */
        $classname = $id;
        $ref = new ReflectionClass($classname);

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

        return $this->get($class->getName());
    }
}
