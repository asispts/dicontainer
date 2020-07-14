<?php declare(strict_types=1);

namespace Xynha\Container;

use Psr\Container\ContainerInterface;

final class DiContainer implements ContainerInterface
{

    /** @var DiRuleList */
    private $rule;

    /** @var DiBuilder */
    private $builder;

    public function __construct(DiRuleList $rule)
    {
        $this->rule = $rule;
        $this->builder = new DiBuilder($this);
    }

    /**
     * @param string $id
     */
    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(sprintf('Class or rule `%s` is not found or it is an interface', $id));
        }

        return $this->builder->createObject($id);
    }

    /**
     * This code is intended for test coverage purpose.
     * Do not simplify!
     *
     * @param string $id
     */
    public function has($id)
    {
        if ($this->rule->hasRule($id)) {
            return true;
        }

        if (class_exists($id)) {
            return true;
        }

        return false;
    }
}
