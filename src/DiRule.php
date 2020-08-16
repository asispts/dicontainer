<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

    /** @var class-string */
    private $className;

    /** @var bool */
    private $shared = false;

    /** @var array<int,mixed> */
    private $params;

    /** @var array<string,string> */
    private $interfaces;

    /** @param array<string,mixed> $rules */
    public function __construct(string $key, array $rules)
    {
        $this->key = $key;

        $this->className = $rules['instanceOf'] ?? $key;
        $this->shared = (bool)($rules['shared'] ?? false);
        $this->params = $rules['constructParams'] ?? [];
        $this->interfaces = $rules['substitutions'] ?? [];
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
        return $this->shared;
    }

    /** @return array<int,mixed> */
    public function getParams() : array
    {
        return $this->params;
    }

    /** @return array<string,string> */
    public function getSubstitutions() : array
    {
        return $this->interfaces;
    }
}
