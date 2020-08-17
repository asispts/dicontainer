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
class ConstructParamsTest extends DiceTest
{

    public function testConstructParams()
    {
        $rule['constructParams'] = array('foo', 'bar');
        $rlist = $this->rlist->addRule(new DiRule('RequiresConstructorArgsA', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('RequiresConstructorArgsA');

        $this->assertEquals($obj->foo, 'foo');
        $this->assertEquals($obj->bar, 'bar');
    }

    public function testInternalClass()
    {
        $rule['constructParams'][] = '.';
        $rlist = $this->rlist->addRule(new DiRule('DirectoryIterator', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('DirectoryIterator');

        $this->assertInstanceOf('DirectoryIterator', $obj);
    }

    public function testInternalClassExtended()
    {
        $rule['constructParams'][] = '.';
        $rlist = $this->rlist->addRule(new DiRule('MyDirectoryIterator', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('MyDirectoryIterator');

        $this->assertInstanceOf('MyDirectoryIterator', $obj);
    }

    public function testInternalClassExtendedConstructor()
    {
        $rule['constructParams'][] = '.';
        $rlist = $this->rlist->addRule(new DiRule('MyDirectoryIterator2', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('MyDirectoryIterator2');

        $this->assertInstanceOf('MyDirectoryIterator2', $obj);
    }

    public function testDefaultNullAssigned()
    {
        $rule['constructParams'] = [ ['.:INSTANCE:.' => 'A'], null];
        $rlist = $this->rlist->addRule(new DiRule('MethodWithDefaultNull', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('MethodWithDefaultNull');

        $this->assertNull($obj->b);
    }

    public function testConstructParamsNested()
    {
        if (DIC_CONSTRUCT_PARAMS === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule1['constructParams'] = array('foo', 'bar');
        $rule2['shareInstances'] = array('D');

        $rlist = $this->rlist->addRule(new DiRule('RequiresConstructorArgsA', $rule1));
        $rlist = $rlist->addRule(new DiRule('ParamRequiresArgs', $rule2));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('ParamRequiresArgs');

        $this->assertEquals($obj->a->foo, 'foo');
        $this->assertEquals($obj->a->bar, 'bar');
    }

    public function testConstructParamsMixed()
    {
        $rule['constructParams'] = array('foo', 'bar');
        $rlist = $this->rlist->addRule(new DiRule('RequiresConstructorArgsB', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('RequiresConstructorArgsB');

        $this->assertEquals($obj->foo, 'foo');
        $this->assertEquals($obj->bar, 'bar');
        $this->assertInstanceOf('A', $obj->a);
    }

    public function testSharedClassWithTraitExtendsInternalClass()
    {
        $rule['constructParams'] = ['.'];
        $rlist = $this->rlist->addRule(new DiRule('MyDirectoryIteratorWithTrait', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('MyDirectoryIteratorWithTrait');

        $this->assertInstanceOf('MyDirectoryIteratorWithTrait', $obj);
    }

    public function testConstructParamsPrecedence()
    {
        $this->markTestSkipped('Unsupported feature');
    }

    public function testNullScalar()
    {
        $rule['constructParams'] = [null];
        $rlist = $this->rlist->addRule(new DiRule('NullScalar', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('NullScalar');

        $this->assertEquals(null, $obj->string);
    }

    public function testNullScalarNested()
    {
        $rule['constructParams'] = [null];
        $rlist = $this->rlist->addRule(new DiRule('NullScalar', $rule));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('NullScalarNested');

        $this->assertEquals(null, $obj->nullScalar->string);
    }
}
