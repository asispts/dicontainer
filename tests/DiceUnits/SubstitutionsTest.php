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
class SubstitutionsTest extends DiceTest
{

    public function testNoMoreAssign()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Bar77'] = [
                                           'Dice::INSTANCE' => function () {
                                                    return Baz77::create(); // @phpstan-ignore-line
                                           }
                                          ];

        $list = $this->rlist->addRule(new DiRule('Foo77', $rule));
        $dic = new DiContainer($list);
        $foo = $dic->get('Foo77');

        $this->assertInstanceOf('Bar77', $foo->bar);
        $this->assertEquals('Z', $foo->bar->a);
    }

    public function testNullSubstitution()
    {
        $rule['substitutions']['B'] = null;
        $list = $this->rlist->addRule(new DiRule('MethodWithDefaultNull', $rule));
        $dic = new DiContainer($list);
        $obj = $dic->get('MethodWithDefaultNull');

        $this->assertNull($obj->b);
    }

    public function testSubstitutionText()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'ExtendedB'];
        $list = $this->rlist->addRule(new DiRule('A', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionTextMixedCase()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'exTenDedb'];
        $list = $this->rlist->addRule(new DiRule('A', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionCallback()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $injection = new DiContainer($this->rlist);
        $rule['substitutions']['B'] = [
                                       'Dice::INSTANCE' => function () use ($injection) {
                                                return $injection->get('ExtendedB');
                                       }
                                      ];

        $list = $this->rlist->addRule(new DiRule('A', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionObject()
    {
        $oldDic = new DiContainer($this->rlist);
        $rule['substitutions']['B'] = $oldDic->get('ExtendedB');

        $list = $this->rlist->addRule(new DiRule('A', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionString()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'ExtendedB'];
        $list = $this->rlist->addRule(new DiRule('A', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubFromString()
    {
        $rule = ['substitutions' => ['Bar' => 'Baz']];
        $list = $this->rlist->addRule(new DiRule('*', $rule));
        $dic = new DiContainer($list);
        $obj = $dic->get('Foo');

        $this->assertInstanceOf('Baz', $obj->bar);
    }

    public function testSubstitutionWithFuncCall()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Bar'] = ['Dice::INSTANCE' => ['Foo2', 'bar']];
        $list = $this->rlist->addRule(new DiRule('Foo', $rule));
        $dic = new DiContainer($list);
        $a = $dic->get('Foo');

        $this->assertInstanceOf('Baz', $a->bar);
    }
}
