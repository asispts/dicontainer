<?php declare(strict_types=1);

namespace Xynha\Container;

use Psr\Container\ContainerInterface;
use Throwable;

abstract class AbstractDiContainer implements ContainerInterface
{

    /** @var DiRuleList */
    protected $list;

    /** @var DiParser */
    protected $parser;

    /** @var array<string,string> */
    private $curKeys = [];

    abstract protected function createObject(DiRule $rule) : Object;

    final public function __construct(DiRuleList $list)
    {
        $this->list = $list;
        $this->parser = new DiParser([$this, 'get']);
    }

    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule `%s` is not found or it is an interface', $id));
        }

        $rule = $this->list->getRule($id);
        if (array_key_exists($rule->getKey(), $this->curKeys) || in_array($rule->getClassname(), $this->curKeys)) {
            throw new ContainerException('Circular dependencies detected');
        }

        try {
            $this->curKeys[$rule->getKey()] = $rule->getClassname();
            $object = $this->createObject($rule);
            unset($this->curKeys[$rule->getKey()]);
            return $object;
        } catch (Throwable $exc) {
            unset($this->curKeys[$rule->getKey()]);
            throw $exc;
        }
    }

    public function has($id)
    {
        if ($this->list->hasRule($id) || class_exists($id)) {
            return true;
        }

        return false;
    }
}
