<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

    /** @var bool */
    private $shared = false;

    public function __construct(string $key)
    {
        $this->key = $key;
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

    /** @return void */
    public function setShared(bool $shared)
    {
        $this->shared = $shared;
    }

    public function isShared() : bool
    {
        return $this->shared;
    }
}
