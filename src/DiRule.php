<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

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
        /** @var class-string $classname */
        $classname = $this->key;
        return $classname;
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
