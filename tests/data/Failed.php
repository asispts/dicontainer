<?php

abstract class AbstractFailed
{

    public function __construct()
    {
    }
}

class PrivateFailed
{

    private function __construct()
    {
    }
}

class ProtectedFailed
{

    protected function __construct()
    {
    }
}
