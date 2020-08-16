<?php

class Factory
{

    public function get()
    {
        return new FactoryDependency;
    }
}

class FactoryDependency
{

    public function getBar()
    {
        return 'bar';
    }
}

class RequiresFactoryDependecy
{

    public $dep;

    public function __construct(FactoryDependency $dep)
    {
        $this->dep = $dep;
    }
}
