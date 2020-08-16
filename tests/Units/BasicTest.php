<?php declare(strict_types=1);

namespace Xynha\Tests\Units;

use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Data\NoConstructor;
use Xynha\Tests\Data\SharedInstance;

final class BasicTest extends AbstractTestCase
{

    public function testNoConstructor()
    {
        $obj = $this->dic->get(NoConstructor::class);
        $this->assertInstanceOf(NoConstructor::class, $obj);
    }

    public function testCreateObjectGraph()
    {
        $graph = $this->dic->get(ClassGraph::class);

        $this->assertInstanceOf('Xynha\Tests\Data\B', $graph->b);
        $this->assertInstanceOf('Xynha\Tests\Data\C', $graph->b->c);
        $this->assertInstanceOf('Xynha\Tests\Data\D', $graph->b->c->d);
        $this->assertInstanceOf('Xynha\Tests\Data\E', $graph->b->c->e);
        $this->assertInstanceOf('Xynha\Tests\Data\F', $graph->b->c->e->f);
    }

    public function testSharedInstance()
    {
        $this->rlist->newRule(SharedInstance::class, ['shared' => true]);

        $objA = $this->dic->get(SharedInstance::class);
        $objB = $this->dic->get(SharedInstance::class);

        $objA->arg = 'Replace value';
        $this->assertSame($objA->arg, $objB->arg);
    }
}
