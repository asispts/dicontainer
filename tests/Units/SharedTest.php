<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\E;
use Xynha\Tests\Data\ObjectAllowsNull;

final class SharedTest extends AbstractTestCase
{

    public function testSharedInstance()
    {
        $rlist = $this->rlist->addRule(new DiRule(ObjectAllowsNull::class, ['shared' => true]));
        $dic = new DiContainer($rlist);

        $objA = $dic->get(ObjectAllowsNull::class);
        $objB = $dic->get(ObjectAllowsNull::class);

        $this->assertSame($objA, $objB);

        $objA->std = new stdClass();
        $this->assertSame($objA->std, $objB->std);
    }

    public function testSharedArgument()
    {
        $rlist = $this->rlist->addRule(new DiRule(E::class, ['shared' => true]));
        $dic = new DiContainer($rlist);

        $objA = $dic->get(ClassGraph::class);
        $objB = $dic->get(ClassGraph::class);

        $this->assertNotSame($objA, $objB);
        $this->assertSame($objA->b->c->e, $objB->b->c->e);

        $objB->b->c->e->f = null;
        $this->assertNull($objA->b->c->e->f);
    }
}
