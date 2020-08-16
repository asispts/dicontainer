<?php declare(strict_types=1);

namespace Xynha\Container;

use Psr\Container\ContainerInterface;

abstract class AbstractDiContainer implements ContainerInterface
{

    /** @var DiRuleList */
    protected $list;

    abstract protected function createObject(DiRule $rule) : Object;

    final public function __construct(DiRuleList $list)
    {
        $this->list = $list;
    }

    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule `%s` is not found or it is an interface', $id));
        }

        $rule = $this->list->hasRule($id) ? $this->list->getRule($id) : $this->list->newRule($id);
        return $this->createObject($rule);
    }

    public function has($id)
    {
        if ($this->list->hasRule($id)) {
            return true;
        }

        if (class_exists($id)) {
            return true;
        }

        return false;
    }
}
