<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRuleList
{

    /** @var array<string,DiRule> */
    private $rules = [];

    public function addRule(DiRule $rule) : self
    {
        $new = clone $this;
        $this->addToRule($new, $rule);
        return $new;
    }

    public function getRule(string $key) : DiRule
    {
        $rule = $this->findRule($key);

        if (array_key_exists('*', $this->rules)) {
            $rule->addGlobalRule($this->rules['*']);
        }

        return $rule;
    }

    public function hasRule(string $key) : bool
    {
        return array_key_exists($key, $this->rules);
    }

    private function addToRule(DiRuleList $list, DiRule $rule) : void
    {
        $list->rules[$rule->getKey()] = $rule;
    }

    private function findRule(string $key) : DiRule
    {
        if ($this->hasRule($key)) {
            return $this->rules[$key];
        }

        foreach ($this->rules as $rule) {
            if (is_subclass_of($key, $rule->getClassname())) {
                $newRule = new DiRule($key, []);
                $newRule->cloneFrom($rule);
                $this->addToRule($this, $newRule);
                return $newRule;
            }
        }

        if (class_exists($key)) {
            $rule = new DiRule($key, []);
            $this->addToRule($this, $rule);
            return $rule;
        }

        throw new NotFoundException(sprintf('Rule %s is not found', $key));
    }
}
