<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Data\ObjectDefaultValue;
use Xynha\Tests\Data\ScalarRequired;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class ConstructParamsTest extends AbstractConfigTestCase
{

    protected function setUp()
    {
        $this->files = ['BasicClass.php'];
        parent::setUp();
    }

    public function testScalarType()
    {
        $obj = $this->dic->get(ScalarRequired::class);

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

    public function testUseDefaultValue()
    {
        $rules['constructParams'] = [['invalid type']];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(ObjectDefaultValue::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertNull($obj->obj);
    }

    public function testPassInstanceToOptionalArg()
    {
        $passedObj = $this->dic->get(ClassGraph::class);
        $rules['constructParams'] = [$passedObj];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(ObjectDefaultValue::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(ObjectDefaultValue::class);

        $this->assertSame($passedObj, $obj->obj);
    }
}
