<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
use Xynha\Container\DiContainer;
use Xynha\Tests\Data\ClassGraph;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class SharedTest extends AbstractConfigTestCase
{

    protected function setUp()
    {
        $this->files = ['BasicClass.php'];
        parent::setUp();
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

    public function testSharedInstance()
    {
        $objA = $this->dic->get(stdClass::class);
        $objB = $this->dic->get(stdClass::class);

        $this->assertSame($objA, $objB);

        $objA->value = 'Test shared';
        $this->assertSame($objA->value, $objB->value);
    }

    public function testOverrideSharedRule()
    {
        $rlist = $this->rlist->addRule(stdClass::class, ['shared' => false]);
        $dic = new DiContainer($rlist);

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertNotSame($objA, $objB);
    }
}
