<?php declare(strict_types=1);

namespace Xynha\Tests\Units;

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Tests\Units\AbstractTestCase;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Data\ObjectDefaultValue;
use Xynha\Tests\Data\ScalarAllowsNull;
use Xynha\Tests\Data\ScalarRequired;

final class ConstructParamsTest extends AbstractTestCase
{

    public function testScalarType()
    {
        $rules['constructParams'] = [
                                     true,
                                     'String value',
                                     2020,
                                     0.1,
                                     [],
                                     [false, true],
                                     ['string', 'value'],
                                     [2019, 2020],
                                     [0.1, 0.2],
                                    ];

        $rlist = $this->rlist->addRule(ScalarRequired::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ScalarRequired::class);

        $this->assertInstanceOf(ScalarRequired::class, $obj);
        $this->assertSame(true, $obj->bool);
        $this->assertSame('String value', $obj->string);
        $this->assertSame(2020, $obj->int);
        $this->assertSame(0.1, $obj->float);
        $this->assertSame([], $obj->emptyArray);
        $this->assertSame([false, true], $obj->boolArray);
        $this->assertSame(['string', 'value'], $obj->stringArray);
        $this->assertSame([2019, 2020], $obj->intArray);
        $this->assertSame([0.1, 0.2], $obj->floatArray);
    }

    public function testPassInvalidScalarType()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Argument 1 passed to %s::__construct() must be of the type bool or null, array given',
                ScalarAllowsNull::class
            )
        );

        $rules['constructParams'] = [[]];

        $rlist = $this->rlist->addRule(ScalarAllowsNull::class, $rules);
        $dic = new DiContainer($rlist);
        $dic->get(ScalarAllowsNull::class);
    }

    public function testInvalidObjectValueArray()
    {
        $rules['constructParams'] = [['invalid type']];
        $rlist = $this->rlist->addRule(ObjectDefaultValue::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertNull($obj->obj);
    }

    public function testInvalidObjectValueScalar()
    {
        $rules['constructParams'] = ['invalid type'];
        $rlist = $this->rlist->addRule(ObjectDefaultValue::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertNull($obj->obj);
    }

    public function testPassOptionalObject()
    {
        $rules['constructParams'] = [new ObjectAllowsNull(null)];
        $rlist = $this->rlist->addRule(ObjectDefaultValue::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectAllowsNull::class, $obj->obj);
    }
}
