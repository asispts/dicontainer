<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionMethod;

final class ClassInfo
{

    /** @var ParamInfo[] */
    private $params;

    public function __construct(?ReflectionMethod $con)
    {
        if ($con !== null) {
            $this->parse($con);
        }
    }

    /** @return ParamInfo[] */
    public function getParams() : array
    {
        return $this->params ?? [];
    }

    private function parse(ReflectionMethod $con) : void
    {
        $params = $con->getParameters();

        foreach ($params as $param) {
            $arg = new ParamInfo($param);
            $this->params[$arg->name()] = $arg;
        }
    }
}
