<?php declare(strict_types=1);

use Xynha\Tests\AbstractTestCase;

final class BasicTest extends AbstractTestCase
{

    public function testNoConstructor()
    {
        $this->assertTrue($this->dic->has('NoConstructor'));

        $obj = $this->dic->get('NoConstructor');
        $this->assertInstanceOf('NoConstructor', $obj);
    }

    public function testCreateObjectGraph()
    {
        $a = $this->dic->get('A');

        $this->assertInstanceOf('B', $a->b);
        $this->assertInstanceOf('c', $a->b->c);
        $this->assertInstanceOf('D', $a->b->c->d);
        $this->assertInstanceOf('E', $a->b->c->e);
        $this->assertInstanceOf('F', $a->b->c->e->f);
    }

    public function testObjectWithMixedDefaultValue()
    {
        $obj = $this->dic->get('DateTime');
        $this->assertInstanceOf('DateTime', $obj);
    }

    public function testCreateGenericDefaultValue()
    {
        $dv = $this->dic->get('GenericDefaultValue');

        $this->assertNull($dv->std);
        $this->assertSame('Default', $dv->a);
        $this->assertSame(['Default'], $dv->b);
        $this->assertSame(6, $dv->i);
        $this->assertSame(3.14, $dv->f);
    }
}
