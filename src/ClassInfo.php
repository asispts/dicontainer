<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionMethod;

final class ClassInfo
{

    /** @var ParamInfo[] */
    private $params = [];

    /** @param array<int,mixed> $values */
    public function __construct(?ReflectionMethod $con, array $values)
    {
        if ($con !== null) {
            $this->parse($con, $values);
        }
    }

    /** @return ParamInfo[] */
    public function getParams() : array
    {
        return $this->params ?? [];
    }

    /** @param array<int,mixed> $values */
    private function parse(ReflectionMethod $con, array $values) : void
    {
        $params = $con->getParameters();

        foreach ($params as $param) {
            $arg = new ParamInfo($param, $values);
            $this->params[$arg->name()] = $arg;
        }
    }
}
