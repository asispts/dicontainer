<?php declare(strict_types=1);

namespace Xynha\Container;

use Psr\Container\ContainerInterface;
use Throwable;

abstract class AbstractDiContainer implements ContainerInterface
{

    /** @var DiParser */
    protected $parser;

    /** @var DiRuleList */
    private $list;

    /** @var object[] */
    private $instances;

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
        return $this->doGet($id);
    }

    public function has($id)
    {
        if ($this->list->hasRule($id) || class_exists($id)) {
            return true;
        }

        return false;
    }

    private function doGet(string $id) : object
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule %s does not exist', $id));
        }

        $rule = $this->list->getRule($id);
        if ($rule->getKey() === static::class) {
            return clone $this;
        }

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
}
