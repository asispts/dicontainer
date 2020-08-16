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

        if ($this->list->hasRule($id) === false) {
            $rule = new DiRule($id, []);
            $this->list = $this->list->addRule($rule);
        }

        return $this->createObject($this->list->getRule($id));
    }

    public function has($id)
    {
        if ($this->list->hasRule($id) || class_exists($id)) {
            return true;
        }

        return false;
    }
}
