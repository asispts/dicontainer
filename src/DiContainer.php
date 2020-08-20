<?php declare(strict_types=1);

namespace Xynha\Container;

use Error;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

final class DiContainer implements ContainerInterface
{

    /** @var DiParser */
    private $parser;

    /** @var DiRuleList */
    private $list;

    /** @var object[] */
    private $instances;

    /** @var array<string,string> */
    private $curKeys = [];

    public function __construct(DiRuleList $list)
    {
        $this->list = $list;
        $this->parser = new DiParser([$this, 'get']);
    }

    public function has($id)
    {
        return $this->list->hasRule($id) || class_exists($id);
    }

    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule %s does not exist', $id));
        }

        $rule = $this->list->getRule($id);
        if ($rule->getClassname() === __CLASS__) {
            return clone $this;
        }

        if (empty($rule->getFrom())) {
            return $this->getInstance($rule);
        }

        list($fromClass, $fromMethod, $fromArg) = $rule->getFrom();
        $fromRule = $this->list->getRule($fromClass);
        $object = $this->getInstance($fromRule);
        $callback = [$object, $fromMethod];

        if (is_callable($callback)) {
            return call_user_func_array($callback, $fromArg);
        }

        throw new ContainerException('Rule getFrom is not callable');
    }

    private function getInstance(DiRule $rule) : object
    {
        if (isset($this->instances[$rule->getKey()])) {
            return $this->instances[$rule->getKey()];
        }

        if (array_key_exists($rule->getKey(), $this->curKeys) || in_array($rule->getClassname(), $this->curKeys)) {
            throw new ContainerException('Cyclic dependencies detected');
        }

        $this->curKeys[$rule->getKey()] = $rule->getClassname();

        try {
            $object = $this->createObject($rule);
            unset($this->curKeys[$rule->getKey()]);
        } catch (Throwable $exc) {
            unset($this->curKeys[$rule->getKey()]);
            throw $exc;
        }

        if ($rule->isShared()) {
            $this->instances[$rule->getKey()] = $object;
        }

        return $object;
    }

    private function createObject(DiRule $rule) : Object
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
