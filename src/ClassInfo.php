<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionMethod;

final class ClassInfo
{

    /** @var ParamInfo[] */
    private $params = [];

    public function __construct(?ReflectionMethod $con, DiRule $rule)
    {
        if ($con !== null) {
            $this->parse($con, $rule);
        }
    }

    /** @return ParamInfo[] */
    public function getParams() : array
    {
        return $this->params ?? [];
    }

    private function parse(ReflectionMethod $con, DiRule $rule) : void
    {
        $params = $con->getParameters();
        $values = $rule->getParams();

        foreach ($params as $param) {
            $arg = new ParamInfo($param, $values, $rule->getSubstitutions());
            $this->params[$arg->name()] = $arg;
        }
    }
}
