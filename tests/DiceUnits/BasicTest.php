<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Container\NotFoundException;
use Xynha\Tests\DiceUnits\DiceTest;

/**
 * Dice - A minimal Dependency Injection Container for PHP
 *
 * @author Tom Butler tom@r.je
 * @copyright 2012-2018 Tom Butler <tom@r.je> | https:// r.je/dice.html
 * @license http:// www.opensource.org/licenses/bsd-license.php BSD License
 * @version 3.0
 */
final class BasicTest extends DiceTest
{

    public function testCreate()
    {
        $this->getMockBuilder('TestCreate')->getMock();

        $dic = new DiContainer($this->rlist);
        $myobj = $dic->get('TestCreate');

        $this->assertInstanceOf('TestCreate', $myobj);
    }

    public function testCreateInvalid()
    {
        $this->expectException(NotFoundException::class);
        $dic = new DiContainer($this->rlist);
        $dic->get('SomeClassThatDoesNotExist');
    }

    public function testNoConstructor()
    {
        $dic = new DiContainer($this->rlist);
        $a = $dic->get('NoConstructor');

        $this->assertInstanceOf('NoConstructor', $a);
    }

    public function testSetDefaultRule()
    {
        $global = new DiRule('*', ['shared' => true]);
        $list = $this->rlist->addRule($global);
        $rule = $list->getRule('*');

        $this->assertTrue($rule->isShared());
    }

    public function testDefaultRuleWorks()
    {
        $ruleA = new DiRule('A', []);
        $ruleB = new DiRule('B', []);
        $global = new DiRule('*', ['shared' => true]);

        $list = $this->rlist->addRule($ruleA);
        $list = $list->addRule($global);
        $list = $list->addRule($ruleB);

        $a = $list->getRule('A');
        $b = $list->getRule('B');
        $this->assertTrue($a->isShared());
        $this->assertTrue($b->isShared());

        $dic = new DiContainer($list);
        $a1 = $dic->get('A');
        $a2 = $dic->get('A');

        $this->assertSame($a1, $a2);
    }

    // /*
    // * Object graph creation cannot be tested with mocks because the constructor need to be tested.
    // * You can't set 'expects' on the objects which are created making them redundant for that as well
    // * Need real classes to test with unfortunately.
    // */
    public function testObjectGraphCreation()
    {
        $dic = new DiContainer($this->rlist);
        $a = $dic->get('A');

        $this->assertInstanceOf('B', $a->b);
        $this->assertInstanceOf('c', $a->b->c);
        $this->assertInstanceOf('D', $a->b->c->d);
        $this->assertInstanceOf('E', $a->b->c->e);
        $this->assertInstanceOf('F', $a->b->c->e->f);
    }

    public function testSharedNamed()
    {
        $rule['shared'] = true;
        $rule['instanceOf'] = 'A';

        $list = $this->rlist->addRule(new DiRule('[A]', $rule));
        $dic = new DiContainer($list);

        $a1 = $dic->get('[A]');
        $a2 = $dic->get('[A]');
        $this->assertSame($a1, $a2);
    }

    public function testSharedRule()
    {
        $rule['shared'] = true;
        $list = $this->rlist->addRule(new DiRule('MyObj', $rule));
        $dic = new DiContainer($list);

        $obj = $dic->get('MyObj');
        $this->assertInstanceOf('MyObj', $obj);

        $obj2 = $dic->get('MyObj');
        $this->assertInstanceOf('MyObj', $obj2);

        $this->assertSame($obj, $obj2);

        // //This check isn't strictly needed but it's nice to have that safety measure!
        $obj->setFoo('bar');
        $this->assertEquals($obj->getFoo(), $obj2->getFoo());
        $this->assertEquals($obj->getFoo(), 'bar');
        $this->assertEquals($obj2->getFoo(), 'bar');
    }

    public function testInterfaceRule()
    {
        $rule['shared'] = true;
        $list = $this->rlist->addRule(new DiRule('interfaceTest', $rule));
        $dic = new DiContainer($list);

        $one = $dic->get('InterfaceTestClass');
        $two = $dic->get('InterfaceTestClass');

        $this->assertSame($one, $two);
    }

    public function testCyclicReferences()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cyclic dependencies detected');

        $rule['shared'] = true;
        $list = $this->rlist->addRule(new DiRule('CyclicB', $rule));
        $dic = new DiContainer($list);

        $dic->get('CyclicA');
    }

    public function testInherit()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = ['shared' => true, 'inherit' => false];
        $list = $this->rlist->addRule(new DiRule('ParentClass', $rule));
        $dic = new DiContainer($list);

        $obj1 = $dic->get('Child');
        $obj2 = $dic->get('Child');

        $this->assertNotSame($obj1, $obj2);
    }

    public function testSharedOverride()
    {
        //Set everything to shared by default
        $list = $this->rlist->addRule(new DiRule('*', ['shared' => true]));
        $list = $list->addRule(new DiRule('A', ['shared' => false]));
        $dic = new DiContainer($list);

        $a1 = $dic->get('A');
        $a2 = $dic->get('A');

        $this->assertNotSame($a1, $a2);
    }

    public function testOptionalInterface()
    {
        $dic = new DiContainer($this->rlist);
        $optionalInterface = $dic->get('OptionalInterface');

        $this->assertEquals(null, $optionalInterface->obj);
    }

    public function testScalarTypeHintWithShareInstances()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $list = $this->rlist->addRule(new DiRule('ScalarTypeHint', ['shareInstances' => ['A']]));
        $dic = new DiContainer($list);

        $obj = $dic->get('ScalarTypeHint');

        $this->assertInstanceOf('ScalarTypeHint', $obj);
    }

    public function testPassGlobals()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        //write to the global $_GET variable
        $_GET['foo'] = 'bar';

        $rule['constructParams'] = [
                                    ['Dice::GLOBAL' => '_GET'],
                                   ];
        $list = $this->rlist->addRule(new DiRule('CheckConstructorArgs', $rule));
        $dic = new DiContainer($list);
        $obj = $dic->get('CheckConstructorArgs');

        $this->assertEquals($_GET, $obj->arg1);
    }

    public function testPassConstantString()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }
        $rule['constructParams'] = [
                                    ['Dice::CONSTANT' => '\PDO::FETCH_ASSOC'],
                                   ];
        $list = $this->rlist->addRule(new DiRule('CheckConstructorArgs', $rule));
        $dic = new DiContainer($list);
        $obj = $dic->get('CheckConstructorArgs');

        $this->assertEquals(\PDO::FETCH_ASSOC, $obj->arg1);
    }

    public function testImmutability()
    {
        $this->assertFalse($this->rlist->hasRule('Foo'));
        $this->rlist->addRule(new DiRule('Foo', ['shared' => true]));
        $this->assertFalse($this->rlist->hasRule('Foo'));
    }

    public function testPassSelf()
    {
        $this->markTestSkipped('This feature is not supported');
    }

    // Issue 180
    public function testSlashNoSlash()
    {
        $list = $this->rlist->addRule(new DiRule('\someclass', ['shared' => true]));
        $dic = new DiContainer($list);

        $b = $dic->get('\someotherclass');
        $a = $dic->get('\someclass');

        $this->assertSame($a, $b->obj);
    }
}
