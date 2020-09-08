<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
use PHPUnit\Framework\TestCase;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Data\ObjectDefaultValue;
use Xynha\Tests\Data\ScalarAllowsNull;
use Xynha\Tests\Data\ScalarTypeDefaultValue;

final class NoConfigTest extends TestCase
{

    /** @var DiContainer */
    private $dic;

    protected function setUp()
    {
        $rlist = new DiRuleList();
        $this->dic = new DiContainer($rlist);

        require_once DATA_DIR . '/BasicClass.php';
    }

    public function testInternalClass()
    {
        $obj = $this->dic->get(DateTime::class);
        $dt = date_create();

        $this->assertInstanceOf(DateTime::class, $obj);
        $this->assertSame($obj->format(DATE_W3C), $dt->format(DATE_W3C)); // @phpstan-ignore-line
    }

    public function testNoConstructor()
    {
        $obj = $this->dic->get(stdClass::class);
        $this->assertInstanceOf(stdClass::class, $obj);
    }

    public function testObjectGraph()
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
}
