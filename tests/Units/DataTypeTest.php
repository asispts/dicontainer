<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\CheckArgument;
use Xynha\Tests\Data\ScalarType;
use Xynha\Tests\Data\ScalarTypeDefaultValue;

final class DataTypeTest extends AbstractTestCase
{

    public function testRequiredVariable()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Missing required value for $stringVal');

        $this->dic->get(ScalarType::class);
    }

    public function testAllowsNull()
    {
        $obj = $this->dic->get(CheckArgument::class);
        $this->assertInstanceOf(CheckArgument::class, $obj);
        $this->assertNull($obj->arg);
    }

    public function testScalarDefaultValue()
    {
        $dv = $this->dic->get(ScalarTypeDefaultValue::class);

        $this->assertSame('Default value', $dv->string);
        $this->assertSame(6, $dv->int);
        $this->assertSame(3.14, $dv->float);
        $this->assertSame([], $dv->emptyArray);
        $this->assertSame(['default', 'value'], $dv->stringArray);
        $this->assertSame([6, 11, 7], $dv->intArray);
        $this->assertSame([3.14, 3.8], $dv->floatArray);
    }

    public function testScalarType()
    {
        $rules['constructParams'] = [
                                     'String value',
                                     2020,
                                     0.1,
                                     [],
                                     ['string', 'value'],
                                     [2019, 2020],
                                     [0.1, 0.2],
                                    ];


        $this->rlist->newRule(ScalarType::class, $rules);
        $dic = new DiContainer($this->rlist);

        $obj = $dic->get(ScalarType::class);
        $this->assertSame('String value', $obj->string);
        $this->assertSame(2020, $obj->int);
        $this->assertSame(0.1, $obj->float);
        $this->assertSame([], $obj->emptyArray);
        $this->assertSame(['string', 'value'], $obj->stringArray);
        $this->assertSame([2019, 2020], $obj->intArray);
        $this->assertSame([0.1, 0.2], $obj->floatArray);
    }

    public function testOptionalValue()
    {
        $rules['constructParams'] = ['String value', 2020];


        $this->rlist->newRule(ScalarTypeDefaultValue::class, $rules);
        $dic = new DiContainer($this->rlist);

        $obj = $dic->get(ScalarTypeDefaultValue::class);

        $this->assertSame('String value', $obj->string);
        $this->assertSame(2020, $obj->int);
        $this->assertSame(3.14, $obj->float);
        $this->assertSame([], $obj->emptyArray);
        $this->assertSame(['default', 'value'], $obj->stringArray);
        $this->assertSame([6, 11, 7], $obj->intArray);
        $this->assertSame([3.14, 3.8], $obj->floatArray);
    }
}
