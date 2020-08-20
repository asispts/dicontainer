<?php

namespace Xynha\Tests\Data;

class DicDependant
{

    public $dic;

    public function __construct(\Psr\Container\ContainerInterface $dic)
    {
        $this->dic = $dic;
    }
}

class OverriddenDic implements \Psr\Container\ContainerInterface
{
    public function get($id){}
    public function has($id){}
}
