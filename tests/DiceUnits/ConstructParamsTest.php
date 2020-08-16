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
class ConstructParamsTest extends DiceTest
{

    public function testConstructParams()
    {
        $rule['constructParams'] = array('foo', 'bar');
        $this->rlist->newRule('RequiresConstructorArgsA', $rule);

        $obj = $this->dic->get('RequiresConstructorArgsA');

        $this->assertEquals($obj->foo, 'foo');
        $this->assertEquals($obj->bar, 'bar');
    }

    public function testInternalClass()
    {
        $rule['constructParams'][] = '.';
        $this->rlist->newRule('DirectoryIterator', $rule);
        $dir = $this->dic->get('DirectoryIterator');

        $this->assertInstanceOf('DirectoryIterator', $dir);
    }

    public function testInternalClassExtended()
    {
        $rule['constructParams'][] = '.';
        $this->rlist->newRule('MyDirectoryIterator', $rule);
        $dir = $this->dic->get('MyDirectoryIterator');

        $this->assertInstanceOf('MyDirectoryIterator', $dir);
    }

    public function testInternalClassExtendedConstructor()
    {
        $rule['constructParams'][] = '.';
        $this->rlist->newRule('MyDirectoryIterator2', $rule);
        $dir = $this->dic->get('MyDirectoryIterator2');

        $this->assertInstanceOf('MyDirectoryIterator2', $dir);
    }

    public function testDefaultNullAssigned()
    {
        if (DIC_CONSTRUCT_PARAMS === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['constructParams'] = [ ['Dice::INSTANCE' => 'A'], null];
        $this->rlist->newRule('MethodWithDefaultNull', $rule);
        $obj = $this->dic->get('MethodWithDefaultNull');

        $this->assertNull($obj->b);
    }

    public function testConstructParamsNested()
    {
        if (DIC_CONSTRUCT_PARAMS === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['constructParams'] = array('foo', 'bar');
        $this->rlist->newRule('RequiresConstructorArgsA', $rule);

        $rule = [];
        $rule['shareInstances'] = array('D');
        $this->rlist->newRule('ParamRequiresArgs', $rule);

        $obj = $this->dic->get('ParamRequiresArgs');

        $this->assertEquals($obj->a->foo, 'foo');
        $this->assertEquals($obj->a->bar, 'bar');
    }

    public function testConstructParamsMixed()
    {
        $rule['constructParams'] = array('foo', 'bar');
        $this->rlist->newRule('RequiresConstructorArgsB', $rule);

        $obj = $this->dic->get('RequiresConstructorArgsB');

        $this->assertEquals($obj->foo, 'foo');
        $this->assertEquals($obj->bar, 'bar');
        $this->assertInstanceOf('A', $obj->a);
    }

    public function testSharedClassWithTraitExtendsInternalClass()
    {
        $rule['constructParams'] = ['.'];
        $this->rlist->newRule('MyDirectoryIteratorWithTrait', $rule);
        $dir = $this->dic->get('MyDirectoryIteratorWithTrait');

        $this->assertInstanceOf('MyDirectoryIteratorWithTrait', $dir);
    }

    public function testConstructParamsPrecedence()
    {
        if (DIC_CONSTRUCT_PARAMS === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['constructParams'] = ['A', 'B'];
        $this->rlist->newRule('RequiresConstructorArgsA', $rule);

        $a1 = $this->dic->get('RequiresConstructorArgsA');
        $this->assertEquals('A', $a1->foo);
        $this->assertEquals('B', $a1->bar);

        $a2 = $this->dic->get('RequiresConstructorArgsA', ['C', 'D']);
        $this->assertEquals('C', $a2->foo);
        $this->assertEquals('D', $a2->bar);
    }

    public function testNullScalar()
    {
        $rule['constructParams'] = [null];
        $this->rlist->newRule('NullScalar', $rule);

        $obj = $this->dic->get('NullScalar');
        $this->assertEquals(null, $obj->string);
    }

    public function testNullScalarNested()
    {
        $rule['constructParams'] = [null];
        $this->rlist->newRule('NullScalar', $rule);

        $obj = $this->dic->get('NullScalarNested');
        $this->assertEquals(null, $obj->nullScalar->string);
    }
}
