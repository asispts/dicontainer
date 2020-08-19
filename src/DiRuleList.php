<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRuleList
{

    /** @var array<string,DiRule> */
    private $rules = [];

    /** @param array<string,mixed> $rule */
    public function addRule(string $key, array $rule) : self
    {
        $new = clone $this;
        $this->addToList($new, new DiRule($key, $rule));
        return $new;
    }

    /** @param array<string,array<string,mixed>> $rules */
    public function addRules(array $rules) : self
    {
        $new = clone $this;

        foreach ($rules as $key => $values) {
            $this->addToList($new, new DiRule($key, $values));
        }

        return $new;
    }

    public function getRule(string $key) : DiRule
    {
        $rule = $this->findRule($key);

        if (array_key_exists('*', $this->rules)) {
            $rule->cloneFrom($this->rules['*']);
        }

        return $rule;
    }

    public function hasRule(string $key) : bool
    {
        return array_key_exists($key, $this->rules);
    }

    private function addToList(DiRuleList $list, DiRule $rule) : void
    {
        if ($list->hasRule($rule->getKey())) {
            $oldRule = $list->getRule($rule->getKey());
            $rule->cloneFrom($oldRule);
        }

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
                $this->addToList($this, $newRule);
                return $newRule;
            }
        }

        if (class_exists($key)) {
            $rule = new DiRule($key, []);
            $this->addToList($this, $rule);
            return $rule;
        }

        throw new NotFoundException(sprintf('Rule %s does not exist', $key));
    }
}
