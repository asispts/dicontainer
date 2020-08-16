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
class SubstitutionsTest extends DiceTest
{

    public function testNoMoreAssign()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Bar77'] = [
                                           'Dice::INSTANCE' => function () {
                                                    return Baz77::create();
                                           }
                                          ];

        $this->rlist->newRule('Foo77', $rule);

        $foo = $this->dic->get('Foo77');

        $this->assertInstanceOf('Bar77', $foo->bar);
        $this->assertEquals('Z', $foo->bar->a);
    }

    public function testNullSubstitution()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = null;
        $this->rlist->newRule('MethodWithDefaultNull', $rule);
        $obj = $this->dic->get('MethodWithDefaultNull');

        $this->assertNull($obj->b);
    }

    public function testSubstitutionText()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'ExtendedB'];
        $this->rlist->newRule('A', $rule);
        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionTextMixedCase()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'exTenDedb'];
        $this->rlist->newRule('A', $rule);
        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionCallback()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $injection = $this->dic;
        $rule['substitutions']['B'] = [
                                       'Dice::INSTANCE' => function () use ($injection) {
                                                return $injection->get('ExtendedB');
                                       }
                                      ];

        $this->rlist->newRule('A', $rule);

        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionObject()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = $this->dic->get('ExtendedB');
        $this->rlist->newRule('A', $rule);
        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubstitutionString()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['B'] = ['Dice::INSTANCE' => 'ExtendedB'];
        $this->rlist->newRule('A', $rule);
        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testSubFromString()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = ['substitutions' => ['Bar' => 'Baz']];
        $this->rlist->newRule('*', $rule);
        $obj = $this->dic->get('Foo');

        $this->assertInstanceOf('Baz', $obj->bar);
    }

    public function testSubstitutionWithFuncCall()
    {
        if (DIC_SUBSTITUTION === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['substitutions']['Bar'] = ['Dice::INSTANCE' => ['Foo2', 'bar']];
        $this->rlist->newRule('Foo', $rule);
        $a = $this->dic->get('Foo');

        $this->assertInstanceOf('Baz', $a->bar);
    }
}
