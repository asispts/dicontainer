<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\DiceUnits\DiceTest;

/**
 * Dice - A minimal Dependency Injection Container for PHP
 *
 * @author Tom Butler tom@r.je
 * @copyright 2012-2018 Tom Butler <tom@r.je> | https:// r.je/dice.html
 * @license http:// www.opensource.org/licenses/bsd-license.php BSD License
 * @version 3.0
 */
class NamespaceTest extends DiceTest
{

    public function testNamespaceBasic()
    {
        $dic = new DiContainer($this->rlist);
        $a = $dic->get('Foo\\A');

        $this->assertInstanceOf('Foo\\A', $a);
    }

    public function testNamespaceWithSlash()
    {
        $dic = new DiContainer($this->rlist);
        $a = $dic->get('\\Foo\\A');

        $this->assertInstanceOf('\\Foo\\A', $a);
    }

    public function testNamespaceWithSlashrule()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $list = $this->rlist->addRule(new DiRule('\\Foo\\B', $rule));
        $dic = new DiContainer($list);
        $b = $dic->get('\\Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }

    public function testNamespaceWithSlashruleInstance()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $list = $this->rlist->addRule(new DiRule('\\Foo\\B', $rule));
        $dic = new DiContainer($list);
        $b = $dic->get('\\Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }

    public function testNamespaceTypeHint()
    {
        $rule['shared'] = true;
        $list = $this->rlist->addRule(new DiRule('Bar\\A', $rule));
        $dic = new DiContainer($list);

        $c = $dic->get('Foo\\C');
        $c2 = $dic->get('Foo\\C');

        $this->assertInstanceOf('Bar\\A', $c->a);
        $this->assertNotSame($c, $c2);

        //Check the rule has been correctly recognised for type hinted classes in a different namespace
        $this->assertSame($c2->a, $c->a);
    }

    public function testNamespaceInjection()
    {
        $dic = new DiContainer($this->rlist);
        $b = $dic->get('Foo\\B');

        $this->assertInstanceOf('Foo\\B', $b);
        $this->assertInstanceOf('Foo\\A', $b->a);
    }

    public function testNamespaceRuleSubstitution()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $list = $this->rlist->addRule(new DiRule('Foo\\B', $rule));
        $dic = new DiContainer($list);
        $b = $dic->get('Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }
}
