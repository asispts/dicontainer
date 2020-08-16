<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRuleList
{

    /** @var array<string,DiRule> */
    private $rules = [];

    /** @param array<string,mixed> $rules */
    public function newRule(string $key, array $rules) : DiRule
    {
        $rule = new DiRule($key, $rules);
        $this->addRule($rule);

        return $rule;
    }

    /** @return void */
    public function addRule(DiRule $rule)
    {
        $this->rules[$rule->getKey()] = $rule;
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
