<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRuleList
{

    /** @var array<string,DiRule> */
    private $rules = [];

    public function addRule(DiRule $rule) : self
    {
        $new = clone $this;
        $new->rules[$rule->getKey()] = $rule;
        return $new;
    }

    /** @return DiRule */
    public function getRule(string $key)
    {
        if ($this->hasRule($key)) {
            return $this->rules[$key];
        }

        throw new NotFoundException(sprintf('Rule %s is not found', $key));
    }

    public function hasRule(string $key) : bool
    {
        return array_key_exists($key, $this->rules);
    }
}
