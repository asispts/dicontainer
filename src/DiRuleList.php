<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRuleList
{

    /** @var array<string,DiRule> */
    private $rules = [];

    public function newRule(string $key) : DiRule
    {
        $rule = new DiRule($key);
        $this->addRule($rule);

        return $rule;
    }

    /** @return void */
    public function addRule(DiRule $rule)
    {
        $this->rules[$rule->getKey()] = $rule;
    }

    /** @return DiRule|null */
    public function getRule(string $key)
    {
        if ($this->hasRule($key)) {
            return $this->rules[$key];
        }
    }

    public function hasRule(string $key) : bool
    {
        return array_key_exists($key, $this->rules);
    }
}
