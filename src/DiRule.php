<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

    /** @var class-string */
    private $className;

    /** @var array<string,mixed> */
    private $rules;

    /** @param array<string,mixed> $rules */
    public function __construct(string $key, array $rules)
    {
        $this->key = ltrim($key, '\\');

        $this->className = $rules['instanceOf'] ?? $key;
        $this->rules = $rules;
    }

    public function getKey() : string
    {
        return $this->key;
    }

    /** @return class-string */
    public function getClassname() : string
    {
        return $this->className;
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

    /** @return array<int,array{string,mixed}>|array<int,array{string,mixed,mixed}> */
    public function getCall() : array
    {
        return $this->rules['call'] ?? [];
    }

    public function cloneFrom(DiRule $rule) : void
    {
        $this->rules = array_replace_recursive($rule->rules, $this->rules);
    }
}
