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
class CallTest extends DiceTest
{

    public function testCall()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'][] = array('callMe', array());
        $this->rlist->newRule('TestCall', $rule);
        $object = $this->dic->get('TestCall');

        $this->assertTrue($object->isCalled);
    }

    public function testCallWithParameters()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'][] = array('callMe', array('one', 'two'));
        $this->rlist->newRule('TestCall2', $rule);
        $object = $this->dic->get('TestCall2');

        $this->assertEquals('one', $object->foo);
        $this->assertEquals('two', $object->bar);
    }

    public function testCallWithInstance()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'][] = array('callMe', array(['Dice::INSTANCE' => 'A']));
        $this->rlist->newRule('TestCall3', $rule);
        $object = $this->dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
    }

    public function testCallAutoWireInstance()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'][] = array('callMe', []);
        $this->rlist->newRule('TestCall3', $rule);
        $object = $this->dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
    }

    public function testCallReturnValue()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $returnValue = null;

        // phpcs:disable
        $rule['call'][] = array(
                           'callMe',
                           [],
                            function ($return) use (&$returnValue) {
                                        $returnValue = $return;
                            },
                          );
        // phpcs:enable

        $this->rlist->newRule('TestCall3', $rule);
        $object = $this->dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
        $this->assertEquals('callMe called', $returnValue);
    }

    public function testCallChain()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'] = [
                         ['call1', ['foo'], 'Dice::CHAIN_CALL'],
                         ['call2', ['bar'], 'Dice::CHAIN_CALL'],
                        ];
        $this->rlist->newRule('TestCallImmutable', $rule);
        $object = $this->dic->get('TestCallImmutable');

        $this->assertEquals('foo', $object->a);
        $this->assertEquals('bar', $object->b);
    }

    public function testCallShareVariadic()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        // Shared params should not be passed to variadic call
        $rule['call'] = [
                         ['callMe', ['test1']],
                        ];
        $this->rlist->newRule('TestCallVariadic', $rule);
        $object = $this->dic->get('TestCallVariadic', [], [new F()]);

        $this->assertEquals(['test1'], $object->data);
    }
}
