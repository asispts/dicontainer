<?php declare(strict_types=1);

namespace Xynha\Container;

use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionMethod;
use UnexpectedValueException;

final class CallbackHelper
{

    private $dic;

    public function __construct(ContainerInterface $dic)
    {
        $this->dic = $dic;
    }

    public function normalize(callable $callback) : callable
    {
        try {
            $ref = $this->getReflection($callback);

            if ($ref === null) {
                return $callback;
            }

            if ($ref->isStatic() === false) {
                $classname = $ref->getDeclaringClass()->getName();
                $methodName = $ref->getName();
                $obj = $this->dic->get($classname);
                return [$obj, $methodName];
            }

            return $callback;
        } catch (ReflectionException $exc) {
            return $callback;
        }
    }

    private function getReflection(callable $callback) : ?ReflectionMethod
    {
        switch (true) {
            case is_string($callback):
                return new ReflectionMethod($callback);
            case is_array($callback):
                return $this->fromArray($callback);
        }

        // $callback is an object
        return null;
    }

    private function fromArray(array $callback) : ?ReflectionMethod
    {
        $class = array_shift($callback);
        $method = array_shift($callback);

        if (is_object($class)) {
            return null;
        }

        return new ReflectionMethod($class, $method);
    }
}
