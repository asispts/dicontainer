<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\AllowsNullFactory;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Data\ObjectDefaultValue;
use Xynha\Tests\Data\ScalarAllowsNull;
use Xynha\Tests\Data\ScalarRequired;

final class ConstructParamsTest extends AbstractTestCase
{

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

        $rlist = $this->rlist->addRule(new DiRule(ScalarAllowsNull::class, $rules));
        $dic = new DiContainer($rlist);
        $dic->get(ScalarAllowsNull::class);
    }

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

        $rlist = $this->rlist->addRule(new DiRule(ScalarRequired::class, $rules));
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

    public function testInvalidArrayConstructParams()
    {
        $rules['constructParams'] = [['invalid type']];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertNull($obj->obj);
    }

    public function testInvalidNonArrayConstructParams()
    {
        $rules['constructParams'] = ['invalid type'];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertNull($obj->obj);
    }

    public function testPassOptionalObject()
    {
        $rules['constructParams'] = [new ObjectAllowsNull(null)];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectAllowsNull::class, $obj->obj);
    }

    public function testPassObjectWithInstanceKey()
    {
        $rules['constructParams'] = [['.:INSTANCE:.' => new ObjectAllowsNull(null)]];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectAllowsNull::class, $obj->obj);
    }

    public function testPassClassWithInstanceKey()
    {
        $rules['constructParams'] = [['.:INSTANCE:.' => ObjectAllowsNull::class]];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectAllowsNull::class, $obj->obj);
    }

    public function testAllowsNullFactory()
    {
        $rules['constructParams'] = [['.:INSTANCE:.' => [AllowsNullFactory::class, 'getInstance']]];
        $rlist = $this->rlist->addRule(new DiRule(ObjectDefaultValue::class, $rules));
        $rlist = $rlist->addRule(new DiRule(AllowsNullFactory::class, ['shared' => true]));
        $dic = new DiContainer($rlist);

        $factory = $dic->get(AllowsNullFactory::class);
        $this->assertInstanceOf(AllowsNullFactory::class, $factory);

        $obj = $dic->get(ObjectDefaultValue::class);
        $this->assertInstanceOf(ObjectAllowsNull::class, $obj->obj);

        $this->assertSame($factory->obj, $obj->obj);
    }
}
