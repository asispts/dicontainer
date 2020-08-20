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

    /** @return array{class-string,string,array<mixed>} */
    public function getFrom() : array
    {
        return $this->rules['getFrom'] ?? [];
    }

    public function cloneFrom(DiRule $rule) : void
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

    /** @param array<string,string> $values */
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
