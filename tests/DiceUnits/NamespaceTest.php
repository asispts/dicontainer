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
class NamespaceTest extends DiceTest
{

    public function testNamespaceBasic()
    {
        $a = $this->dic->get('Foo\\A');
        $this->assertInstanceOf('Foo\\A', $a);
    }

    public function testNamespaceWithSlash()
    {
        $a = $this->dic->get('\\Foo\\A');
        $this->assertInstanceOf('\\Foo\\A', $a);
    }

    public function testNamespaceWithSlashrule()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $this->rlist->newRule('\\Foo\\B', $rule);
        $b = $this->dic->get('\\Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }

    public function testNamespaceWithSlashruleInstance()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $this->rlist->newRule('\\Foo\\B', $rule);
        $b = $this->dic->get('\\Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }

    public function testNamespaceTypeHint()
    {
        $rule['shared'] = true;
        $this->rlist->newRule('Bar\\A', $rule);

        $c = $this->dic->get('Foo\\C');
        $this->assertInstanceOf('Bar\\A', $c->a);

        $c2 = $this->dic->get('Foo\\C');
        $this->assertNotSame($c, $c2);

        //Check the rule has been correctly recognised for type hinted classes in a different namespace
        $this->assertSame($c2->a, $c->a);
    }

    public function testNamespaceInjection()
    {
        $b = $this->dic->get('Foo\\B');
        $this->assertInstanceOf('Foo\\B', $b);
        $this->assertInstanceOf('Foo\\A', $b->a);
    }

    public function testNamespaceRuleSubstitution()
    {
        if (DIC_NAMESPACE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Foo\\A'] = ['Dice::INSTANCE' => 'Foo\\ExtendedA'];
        $this->rlist->newRule('Foo\\B', $rule);
        $b = $this->dic->get('Foo\\B');

        $this->assertInstanceOf('Foo\\ExtendedA', $b->a);
    }
}
