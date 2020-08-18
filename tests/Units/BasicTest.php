<?php declare(strict_types=1);

namespace Xynha\Tests\Units;

use ArrayAccess;
use DateTime;
use Xynha\Container\ContainerException;
use Xynha\Container\NotFoundException;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\AbstractClass;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\CyclicA;
use Xynha\Tests\Data\NoConstructor;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Data\ObjectDefaultValue;
use Xynha\Tests\Data\PrivateClass;
use Xynha\Tests\Data\ProtectedClass;
use Xynha\Tests\Data\ScalarAllowsNull;
use Xynha\Tests\Data\ScalarRequired;
use Xynha\Tests\Data\ScalarTypeDefaultValue;
use Xynha\Tests\Data\TraitTest;

final class BasicTest extends AbstractTestCase
{

    public function testInterface()
    {
        $msg = sprintf('Class or rule %s does not exist', 'ArrayAccess');
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ArrayAccess::class);
    }

    public function testTrait()
    {
        $msg = sprintf('Class or rule %s does not exist', TraitTest::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(TraitTest::class);
    }

    public function testAbstractClass()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class ' . AbstractClass::class);

        $this->dic->get(AbstractClass::class);
    }

    public function testPrivateConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . PrivateClass::class);

        $this->dic->get(PrivateClass::class);
    }

    public function testProtectedConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . ProtectedClass::class);

        $this->dic->get(ProtectedClass::class);
    }

    public function testCyclicDependencies()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cyclic dependencies detected');

        $this->dic->get(CyclicA::class);
    }

    public function testDefaultValueInternalFunction()
    {
        $obj = $this->dic->get(DateTime::class);
        $dt = date_create();

        $this->assertInstanceOf(DateTime::class, $obj);
        $this->assertSame($obj->format(DATE_W3C), $dt->format(DATE_W3C)); // @phpstan-ignore-line
    }

    public function testNoConstructor()
    {
        $obj = $this->dic->get(NoConstructor::class);
        $this->assertInstanceOf(NoConstructor::class, $obj);
    }

    public function testScalarTypeDefaultValue()
    {
        $obj = $this->dic->get(ScalarTypeDefaultValue::class);

        $this->assertInstanceOf(ScalarTypeDefaultValue::class, $obj);
        $this->assertSame(false, $obj->bool);
        $this->assertSame('Default value', $obj->string);
        $this->assertSame(6, $obj->int);
        $this->assertSame(3.14, $obj->float);
        $this->assertSame([], $obj->emptyArray);
        $this->assertSame([false, true], $obj->boolArray);
        $this->assertSame(['default', 'value'], $obj->stringArray);
        $this->assertSame([6, 11, 7], $obj->intArray);
        $this->assertSame([3.14, 3.8], $obj->floatArray);
    }

    public function testScalarAllowsNull()
    {
        $obj = $this->dic->get(ScalarAllowsNull::class);

        $this->assertInstanceOf(ScalarAllowsNull::class, $obj);
        $this->assertNull($obj->bool);
        $this->assertNull($obj->string);
        $this->assertNull($obj->int);
        $this->assertNull($obj->float);
        $this->assertNull($obj->array);
    }

    public function testScalarRequired()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'bool',
                ScalarRequired::class
            )
        );

        $this->dic->get(ScalarRequired::class);
    }

    public function testCreateObjectGraph()
    {
        $graph = $this->dic->get(ClassGraph::class);

        $this->assertInstanceOf(ClassGraph::class, $graph);
        $this->assertInstanceOf('Xynha\Tests\Data\B', $graph->b);
        $this->assertInstanceOf('Xynha\Tests\Data\C', $graph->b->c);
        $this->assertInstanceOf('Xynha\Tests\Data\D', $graph->b->c->d);
        $this->assertInstanceOf('Xynha\Tests\Data\E', $graph->b->c->e);
        $this->assertInstanceOf('Xynha\Tests\Data\F', $graph->b->c->e->f);
    }

    public function testObjectAllowsNull()
    {
        $obj = $this->dic->get(ObjectAllowsNull::class);

        $this->assertInstanceOf(ObjectAllowsNull::class, $obj);
        $this->assertNull($obj->std);
    }

    public function testObjectDefaultValue()
    {
        $obj = $this->dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectDefaultValue::class, $obj);
        $this->assertNull($obj->obj);
    }
}
