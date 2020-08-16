<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var bool */
    private $global = false;

    /** @var string */
    private $key;

    /** @var class-string */
    private $className;

    /** @var array<string,mixed> */
    private $rules;

    /** @param array<string,mixed> $rules */
    public function __construct(string $key, array $rules)
    {
        $this->key = $key;

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

    public function cloneFrom(DiRule $rule) : void
    {
        foreach ($rule->rules as $key => $value) {
            switch ($key) {
                case 'shared':
                    if (array_key_exists($key, $this->rules) === false) {
                        $this->rules[$key] = $value;
                    }
                    break;
                case 'constructParams':
                case 'substitutions':
                    $this->rules[$key] = array_merge($this->rules[$key], $value);
                    break;
            }
        }
    }

    public function addGlobalRule(DiRule $rule): void
    {
        if ($this->global === false) {
            $this->global = true;
            $this->cloneFrom($rule);
        }
    }
}
