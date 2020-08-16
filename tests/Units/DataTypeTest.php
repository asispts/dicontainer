<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\CheckArgument;
use Xynha\Tests\Data\ScalarType;

final class DataTypeTest extends AbstractTestCase
{

    public function testAllowsNull()
    {
        $obj = $this->dic->get(CheckArgument::class);
        $this->assertInstanceOf(CheckArgument::class, $obj);
    }

    public function testRequiredVariable()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Required value for variable $stringVal');

        $this->dic->get(ScalarType::class);
    }

    public function testScalarType()
    {
        $obj = $this->dic->get(ScalarType::class);
        $this->assertInstanceOf(ScalarType::class, $obj);
    }
}
