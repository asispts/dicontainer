<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

    /** @var array<string,mixed> */
    private $rules;

    /** @param array<string,mixed> $rules */
    public function __construct(string $key, array $rules)
    {
        $this->key = ltrim($key, '\\');
        $this->rules = $rules;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    /** @return class-string */
    public function getClassname() : string
    {
        return $this->rules['instanceOf'] ?? $this->getKey();
    }

    public function isShared() : bool
    {
        return (bool)($this->rules['shared'] ?? false);
    }

    /** @return array<int,mixed> */
    public function getParams() : array
    {
        return $this->rules['constructParams'] ?? [];
    }

    /** @return array<string,string> */
    public function getSubstitutions() : array
    {
        return $this->rules['substitutions'] ?? [];
    }

    /** @return array<array<mixed>> */
    public function call() : array
    {
        return $this->rules['call'] ?? [];
    }

    public function getFrom() : array
    {
        return $this->rules['getFrom'] ?? [];
    }

    public function cloneFrom(DiRule $rule) : void
    {
        switch ($rule->getKey()) {
            case '*':
                $this->inheritRule($rule);
                return;
            case $this->getKey(): // same key
                $this->mergeRule($rule);
                return;
            default:
                throw new ContainerException('Unimplemented feature');
        }
    }

    private function inheritRule(DiRule $rule) : void
    {
        foreach ($rule->rules as $key => $values) {
            switch ($key) {
                case 'instanceOf':
                case 'shared':
                case 'constructParams':
                    if (!isset($this->rules[$key])) {
                        $this->rules[$key] = $values;
                    }
                    break;
                case 'substitutions':
                    $this->inheritInterface($values);
                    break;
                case 'call':
                    // Do not overwrite call
                    break;
            }
        }
    }

    private function mergeRule(DiRule $rule) : void
    {
        foreach ($rule->rules as $key => $values) {
            switch ($key) {
                case 'instanceOf':
                case 'shared':
                case 'constructParams':
                case 'call':
                    $this->rules[$key] = $values;
                    break;
                case 'substitutions':
                    $this->mergeInterface($values);
                    break;
            }
        }
    }

    private function inheritInterface(array $values) : void
    {
        if (!isset($this->rules['substitutions'])) {
            $this->rules['substitutions'] = $values;
            return;
        }

        foreach ($values as $key => $class) {
            if (!array_key_exists($key, $this->rules['substitutions'])) {
                $this->rules['substitutions'][$key] = $class;
            }
        }
    }

    private function mergeInterface(array $values) : void
    {
        if (!isset($this->rules['substitutions'])) {
            $this->rules['substitutions'] = $values;
            return;
        }

        foreach ($values as $key => $class) {
            $this->rules['substitutions'][$key] = $class;
        }
    }
}
