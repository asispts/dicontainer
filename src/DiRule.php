<?php declare(strict_types=1);

namespace Xynha\Container;

final class DiRule
{

    /** @var string */
    private $key;

    /** @var bool */
    private $shared;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getKey() : string
    {
        return $this->key;
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
