<?php declare(strict_types=1);

use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\ObjectAllowsNull;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class SharedTest extends AbstractConfigTestCase
{

    protected function setUp()
    {
        $this->files = ['BasicClass.php'];
        parent::setUp();
    }

    public function testSharedInstance()
    {
        $objA = $this->dic->get(ObjectAllowsNull::class);
        $objB = $this->dic->get(ObjectAllowsNull::class);

        $this->assertSame($objA, $objB);

        $objA->std = $this->dic->get(ClassGraph::class);
        $this->assertSame($objA->std, $objB->std);
    }

    public function testSharedArgument()
    {
        $objA = $this->dic->get(ClassGraph::class);
        $objB = $this->dic->get(ClassGraph::class);

        $this->assertNotSame($objA, $objB);
        $this->assertSame($objA->b->c->e, $objB->b->c->e);
        $this->assertNotNull($objA->b->c->e->f);

        $objB->b->c->e->f = null;
        $this->assertNull($objA->b->c->e->f);
    }
}
