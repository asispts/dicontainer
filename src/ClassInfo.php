<?php declare(strict_types=1);

namespace Xynha\Container;

use ReflectionClass;
use ReflectionMethod;

final class ClassInfo
{

    /** @var string */
    private $className;

    /** @var ParamInfo[] */
    private $params = [];

    public function __construct(?ReflectionMethod $con, DiRule $rule)
    {
        $this->className = $rule->getClassname();
        if ($con !== null) {
            $this->parse($con, $rule);
        }
    }

    public function className() : string
    {
        return $this->className;
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
