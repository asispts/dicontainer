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
class ChainTest extends DiceTest
{

    public function testChainCall()
    {
        if (DIC_CHAIN === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'instanceOf' => 'Factory',
                 'call'       => [
                                  ['get', [], 'Dice::CHAIN_CALL'],
                                 ]
                ];
        $this->rlist->newRule('$someClass', $rule);
        $obj = $this->dic->get('$someClass');

        $this->assertInstanceOf('FactoryDependency', $obj);
    }

    public function testMultipleChainCall()
    {
        if (DIC_CHAIN === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'instanceOf' => 'Factory',
                 'call'       => [
                                  ['get', [], 'Dice::CHAIN_CALL'],
                                  ['getBar', [], 'Dice::CHAIN_CALL'],
                                 ]
                ];
        $this->rlist->newRule('$someClass', $rule);
        $obj = $this->dic->get('$someClass');

        $this->assertEquals('bar', $obj);
    }

    public function testChainCallShared()
    {
        if (DIC_CHAIN === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'shared'     => true,
                 'instanceOf' => 'Factory',
                 'call'       => [
                                  ['get', [], 'Dice::CHAIN_CALL'],
                                 ]
                ];
        $this->rlist->newRule('$someClass', $rule);
        $obj = $this->dic->get('$someClass');

        $this->assertInstanceOf('FactoryDependency', $obj);
    }

    public function testChainCallInject()
    {
        if (DIC_CHAIN === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'instanceOf' => 'Factory',
                 'call'       => [
                                  ['get', [], 'Dice::CHAIN_CALL'],
                                 ]
                ];
        $this->rlist->newRule('FactoryDependency', $rule);
        $obj = $this->dic->get('RequiresFactoryDependecy');

        $this->assertInstanceOf('FactoryDependency', $obj->dep);
    }

    public function testChainCallInjectShared()
    {
        if (DIC_CHAIN === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = [
                 'shared'     => true,
                 'instanceOf' => 'Factory',
                 'call'       => [
                                  ['get', [], 'Dice::CHAIN_CALL'],
                                 ]
                ];
        $this->rlist->newRule('FactoryDependency', $rule);
        $this->dic->get('FactoryDependency');

        $obj = $this->dic->get('RequiresFactoryDependecy');
        $this->assertInstanceOf('FactoryDependency', $obj->dep);

        $obj2 = $this->dic->get('RequiresFactoryDependecy');

        $this->assertNotSame($obj, $obj2);
        $this->assertSame($obj->dep, $obj2->dep);
    }
}
