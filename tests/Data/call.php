<?php

namespace Xynha\Tests\Data;

class PdoUnit
{
    public $attr;
    public function setAttribute(int $attribute, string $value)
    {
        $this->attr[$attribute] = $value;
    }
}

class DbUnit
{
    public $pdo;
    public function __construct(PdoUnit $pdo)
    {
        $this->pdo = $pdo;
    }
}

class PdoUnitFactory
{
    public $pdo;
    public function __construct(PdoUnit $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo() : PdoUnit
    {
        return $this->pdo;
    }
}

class ChainUnit
{
    public $data;
    public function chain1(string $value)
    {
        $new = clone $this;
        $new->data[] = $value;
        return $new;
    }

    public function chain2(array $values)
    {
        $new = clone $this;
        $new->data[] = $values;
        return $new;
    }

    public function chain3(string $string, array $values)
    {
        $new = clone $this;
        $new->data[] = $string;
        $new->data[] = $values;
        return $new;
    }

    public function chain4()
    {
        $new = clone $this;
        $new->data[] = 'No value';
        return $new;
    }
}
