<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

final class DiContainer implements ContainerInterface
{

    /**
     * @param class-string|string $id
     */
    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule `%s` is not found or it is an interface', $id));
        }

        /** @var class-string $classname */
        $classname = $id;
        return $this->createObject($classname, []);
    }

    /**
     * @param class-string|string $id
     */
    public function has($id)
    {
        return class_exists($id);
    }

    /**
     * @param class-string $id
     * @param array<mixed> $args
     */
    private function createObject(string $id, array $args) : object
    {
        $ref = new ReflectionClass($id);

        try {
            return $ref->newInstanceArgs($args);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        } catch (ReflectionException $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }
}
