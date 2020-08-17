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

class CallTest extends DiceTest
{

    public function testCall()
    {
        $rule['call'][] = array('callMe', array());
        $rlist = $this->rlist->addRule(new DiRule('TestCall', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCall');

        $this->assertTrue($object->isCalled);
    }

    public function testCallWithParameters()
    {
        $rule['call'][] = array('callMe', array('one', 'two'));
        $rlist = $this->rlist->addRule(new DiRule('TestCall2', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCall2');

        $this->assertEquals('one', $object->foo);
        $this->assertEquals('two', $object->bar);
    }

    public function testCallWithInstance()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['call'][] = array('callMe', array(['Dice::INSTANCE' => 'A']));
        $rlist = $this->rlist->addRule(new DiRule('TestCall3', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
    }

    public function testCallAutoWireInstance()
    {
        $rule['call'][] = array('callMe', []);
        $rlist = $this->rlist->addRule(new DiRule('TestCall3', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
    }

    public function testCallReturnValue()
    {
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

        $rlist = $this->rlist->addRule(new DiRule('TestCall3', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCall3');

        $this->assertInstanceOf('a', $object->a);
        $this->assertEquals('callMe called', $returnValue);
    }

    public function testCallChain()
    {
        if (DIC_CALL === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'call' => [
                            ['call1', ['foo'], 'Dice::CHAIN_CALL'],
                            ['call2', ['bar'], 'Dice::CHAIN_CALL'],
                           ]
                ];
        $rlist = $this->rlist->addRule(new DiRule('TestCallImmutable', $rule));
        $dic = new DiContainer($rlist);
        $object = $dic->get('TestCallImmutable');

        $this->assertEquals('foo', $object->a);
        $this->assertEquals('bar', $object->b);
    }

    public function testCallShareVariadic()
    {
        $this->markTestSkipped('Unsupported feature');
    }
}
