<?php declare(strict_types=1);

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
        $myobj = $this->dic->get('TestCreate');
        $this->assertInstanceOf('TestCreate', $myobj);
    }

    public function testCreateInvalid()
    {
        $this->expectException(NotFoundException::class);

        $this->dic->get('SomeClassThatDoesNotExist');
    }

    public function testNoConstructor()
    {
        $a = $this->dic->get('NoConstructor');
        $this->assertInstanceOf('NoConstructor', $a);
    }

    public function testSetDefaultRule()
    {
        // $defaultBehaviour = [];
        // $defaultBehaviour['shared'] = true;
        // $this->rlist->newRule('*', $defaultBehaviour);

        // $rule = $dice->getRule('*');
        // foreach ($defaultBehaviour as $name => $value) {
        //     $this->assertEquals($rule[$name], $defaultBehaviour[$name]);
        // }
    }

    public function testDefaultRuleWorks()
    {
        // $defaultBehaviour = [];
        // $defaultBehaviour['shared'] = true;

        // $this->rlist->newRule('*', $defaultBehaviour);

        // $rule = $dice->getRule('A');

        // $this->assertTrue($rule['shared']);

        // $a1 = $this->dic->get('A');
        // $a2 = $this->dic->get('A');

        // $this->assertSame($a1, $a2);
    }

    // /*
    // * Object graph creation cannot be tested with mocks because the constructor need to be tested.
    // * You can't set 'expects' on the objects which are created making them redundant for that as well
    // * Need real classes to test with unfortunately.
    // */
    public function testObjectGraphCreation()
    {
        $a = $this->dic->get('A');
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

        $this->rlist->newRule('[A]', $rule);

        $a1 = $this->dic->get('[A]');
        $a2 = $this->dic->get('[A]');
        $this->assertSame($a1, $a2);
    }

    public function testSharedRule()
    {
        $shared['shared'] = true;
        $this->rlist->newRule('MyObj', $shared);

        $obj = $this->dic->get('MyObj');
        $this->assertInstanceOf('MyObj', $obj);

        $obj2 = $this->dic->get('MyObj');
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
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['shared'] = true;
        $this->rlist->newRule('interfaceTest', $rule);

        $one = $this->dic->get('InterfaceTestClass');
        $two = $this->dic->get('InterfaceTestClass');

        $this->assertSame($one, $two);
    }

    public function testCyclicReferences()
    {
        if (DIC_BASIC_CYCLIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['shared'] = true;
        $this->rlist->newRule('CyclicB', $rule);

        $a = $this->dic->get('CyclicA');

        $this->assertInstanceOf('CyclicB', $a->b);
        $this->assertInstanceOf('CyclicA', $a->b->a);

        $this->assertSame($a->b, $a->b->a->b);
    }

    public function testInherit()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule = ['shared' => true, 'inherit' => false];
        $this->rlist->newRule('ParentClass', $rule);

        $obj1 = $this->dic->get('Child');
        $obj2 = $this->dic->get('Child');

        $this->assertNotSame($obj1, $obj2);
    }

    public function testSharedOverride()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        //Set everything to shared by default
        $this->rlist->newRule('*', ['shared' => true]);
        $this->rlist->newRule('A', ['shared' => false]);

        $a1 = $this->dic->get('A');
        $a2 = $this->dic->get('A');

        $this->assertNotSame($a1, $a2);
    }

    public function testOptionalInterface()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $optionalInterface = $this->dic->get('OptionalInterface');

        $this->assertEquals(null, $optionalInterface->obj);
    }

    public function testScalarTypeHintWithShareInstances()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $this->rlist->newRule('ScalarTypeHint', ['shareInstances' => ['A']]);

        $obj = $this->dic->get('ScalarTypeHint');

        $this->assertInstanceOf('ScalarTypeHint', $obj);
    }

    public function testPassGlobals()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        //write to the global $_GET variable
        $_GET['foo'] = 'bar';

        $this->rlist->newRule(
            'CheckConstructorArgs',
            [
             'constructParams' => [
                                   ['Dice::GLOBAL' => '_GET'],
                                  ]
            ]
        );

        $obj = $this->dic->get('CheckConstructorArgs');

        $this->assertEquals($_GET, $obj->arg1);
    }

    public function testPassConstantString()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $this->rlist->newRule(
            'CheckConstructorArgs',
            [
             'constructParams' => [
                                   ['Dice::CONSTANT' => '\PDO::FETCH_ASSOC'],
                                  ]
            ]
        );

        $obj = $this->dic->get('CheckConstructorArgs');

        $this->assertEquals(\PDO::FETCH_ASSOC, $obj->arg1);
    }

    public function testImmutability()
    {
        // $this->assertEquals([], $this->dice->getRule('Foo'));

        // $this->rlist->newRule('Foo', ['shared' => true]);

        // $this->assertEquals([], $this->dice->getRule('Foo'));
    }

    public function testPassSelf()
    {
        // $this->rlist->newRule(
        //     'CheckConstructorArgs',
        //     [
        //      'constructParams' => [
        //                            ['Dice::INSTANCE' => 'Dice::SELF'],
        //                           ]
        //     ]
        // );

        // $obj = $this->dic->get('CheckConstructorArgs');

        // $this->assertEquals($dice, $obj->arg1);
    }

    // Issue 180
    public function testSlashNoSlash()
    {
        if (DIC_BASIC === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $this->rlist->newRule('\someclass', ['shared' => true]);

        $b = $this->dic->get('\someotherclass');
        $a = $this->dic->get('\someclass');

        $this->assertSame($a, $b->obj);
    }
}
