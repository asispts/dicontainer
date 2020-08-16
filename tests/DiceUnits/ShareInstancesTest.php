<?php declare(strict_types=1);

use Xynha\Tests\DiceUnits\DiceTest;

/**
 * Dice - A minimal Dependency Injection Container for PHP
 *
 * @author Tom Butler tom@r.je
 * @copyright 2012-2018 Tom Butler <tom@r.je> | https:// r.je/dice.html
 * @license http:// www.opensource.org/licenses/bsd-license.php BSD License
 * @version 3.0
 */
class ShareInstancesTest extends DiceTest
{

    public function testShareInstances()
    {
        if (DIC_SHARED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['shareInstances'] = ['Shared'];
        $this->rlist->newRule('TestSharedInstancesTop', $rule);

        $shareTest = $this->dic->get('TestSharedInstancesTop');

        $this->assertinstanceOf('TestSharedInstancesTop', $shareTest);

        $this->assertInstanceOf('SharedInstanceTest1', $shareTest->share1);
        $this->assertInstanceOf('SharedInstanceTest2', $shareTest->share2);

        $this->assertSame($shareTest->share1->shared, $shareTest->share2->shared);
        $this->assertEquals($shareTest->share1->shared->uniq, $shareTest->share2->shared->uniq);
    }

    public function testNamedShareInstances()
    {
        if (DIC_SHARED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [];
        $rule['instanceOf'] = 'Shared';
        $this->rlist->newRule('$Shared', $rule);

        $rule = [];
        $rule['shareInstances'] = ['$Shared'];
        $this->rlist->newRule('TestSharedInstancesTop', $rule);


        $shareTest = $this->dic->get('TestSharedInstancesTop');

        $this->assertinstanceOf('TestSharedInstancesTop', $shareTest);

        $this->assertInstanceOf('SharedInstanceTest1', $shareTest->share1);
        $this->assertInstanceOf('SharedInstanceTest2', $shareTest->share2);

        $this->assertSame($shareTest->share1->shared, $shareTest->share2->shared);
        $this->assertEquals($shareTest->share1->shared->uniq, $shareTest->share2->shared->uniq);


        $shareTest2 = $this->dic->get('TestSharedInstancesTop');
        $this->assertNotSame($shareTest2->share1->shared, $shareTest->share2->shared);
    }

    public function testShareInstancesNested()
    {
        if (DIC_SHARED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [];
        $rule['shareInstances'] = ['F'];
        $this->rlist->newRule('A4', $rule);
        $a = $this->dic->get('A4');
        $this->assertTrue($a->m1->f === $a->m2->e->f);
    }

    public function testShareInstancesMultiple()
    {
        if (DIC_SHARED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [];
        $rule['shareInstances'] = ['Shared'];
        $this->rlist->newRule('TestSharedInstancesTop', $rule);


        $shareTest = $this->dic->get('TestSharedInstancesTop');

        $this->assertinstanceOf('TestSharedInstancesTop', $shareTest);

        $this->assertInstanceOf('SharedInstanceTest1', $shareTest->share1);
        $this->assertInstanceOf('SharedInstanceTest2', $shareTest->share2);

        $this->assertSame($shareTest->share1->shared, $shareTest->share2->shared);
        $this->assertEquals($shareTest->share1->shared->uniq, $shareTest->share2->shared->uniq);


        $shareTest2 = $this->dic->get('TestSharedInstancesTop');
        $this->assertSame($shareTest2->share1->shared, $shareTest2->share2->shared);
        $this->assertEquals($shareTest2->share1->shared->uniq, $shareTest2->share2->shared->uniq);

        $this->assertNotSame($shareTest->share1->shared, $shareTest2->share2->shared);
        $this->assertNotEquals($shareTest->share1->shared->uniq, $shareTest2->share2->shared->uniq);
    }
}
