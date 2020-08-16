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
class NamedInstancesTest extends DiceTest
{

    public function testMultipleSharedInstancesByNameMixed()
    {
        if (DIC_NAMED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['shared'] = true;
        $rule['constructParams'][] = 'FirstY';
        $this->rlist->newRule('Y', $rule);

        $rule = [];
        $rule['instanceOf'] = 'Y';
        $rule['shared'] = true;
        $rule['inherit'] = false;
        $rule['constructParams'][] = 'SecondY';

        $this->rlist->newRule('[Y2]', $rule);

        $rule = [];
        $rule['constructParams'] = [ ['Dice::INSTANCE' => 'Y'], ['Dice::INSTANCE' => '[Y2]']];

        $this->rlist->newRule('Z', $rule);

        $z = $this->dic->get('Z');
        $this->assertEquals($z->y1->name, 'FirstY');
        $this->assertEquals($z->y2->name, 'SecondY');
    }

    public function testNonSharedComponentByNameA()
    {
        if (DIC_NAMED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['instanceOf'] = 'ExtendedB';
        $this->rlist->newRule('$B', $rule);

        $rule = [];
        $rule['constructParams'][] = ['Dice::INSTANCE' => '$B'];
        $this->rlist->newRule('A', $rule);

        $a = $this->dic->get('A');
        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testNonSharedComponentByName()
    {
        if (DIC_NAMED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['instanceOf'] = 'Y3';
        $rule['constructParams'][] = 'test';


        $this->rlist->newRule('$Y2', $rule);


        $y2 = $this->dic->get('$Y2');
        //echo $y2->name;
        $this->assertInstanceOf('Y3', $y2);

        $rule = [];

        $rule['constructParams'][] = ['Dice::INSTANCE' => '$Y2'];
        $this->rlist->newRule('Y1', $rule);

        $y1 = $this->dic->get('Y1');
        $this->assertInstanceOf('Y3', $y1->y2);
    }

    public function testSubstitutionByName()
    {
        if (DIC_NAMED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['instanceOf'] = 'ExtendedB';
        $this->rlist->newRule('$B', $rule);

        $rule = [];
        $rule['substitutions']['B'] = ['Dice::INSTANCE' => '$B'];

        $this->rlist->newRule('A', $rule);
        $a = $this->dic->get('A');

        $this->assertInstanceOf('ExtendedB', $a->b);
    }

    public function testMultipleSubstitutions()
    {
        if (DIC_NAMED_INSTANCE === false) {
            $this->markTestIncomplete('Unimplemented feature');
        }

        $rule['instanceOf'] = 'Y2';
        $rule['constructParams'][] = 'first';
        $this->rlist->newRule('$Y2A', $rule);

        $rule = [];
        $rule['instanceOf'] = 'Y2';
        $rule['constructParams'][] = 'second';
        $this->rlist->newRule('$Y2B', $rule);

        $rule = [];
        $rule['constructParams'] = array(['Dice::INSTANCE' => '$Y2A'], ['Dice::INSTANCE' => '$Y2B']);
        $this->rlist->newRule('HasTwoSameDependencies', $rule);

        $twodep = $this->dic->get('HasTwoSameDependencies');

        $this->assertEquals('first', $twodep->y2a->name);
        $this->assertEquals('second', $twodep->y2b->name);
    }

    public function testNamedInstanceCallWithInheritance()
    {
        // $rule1 = [];
        // $rule1['call'] = [
        //                   ['callMe', [1, 3] ],
        //                   ['callMe', [3, 4] ],
        //                  ];

        // $this->rlist->newRule('Y', $rule1);

        // $rule2 = [];
        // $rule2['instanceOf'] = 'Y';
        // $rule2['constructParams'] = ['Foo'];

        // $this->rlist->newRule('$MyInstance', $rule2);

        // $this->assertEquals(array_merge_recursive($rule1, $rule2), $dice->getRule('$MyInstance'));
    }

    public function testNamedInstanceCallWithoutInheritance()
    {
        // $rule1 = [];
        // $rule1['call'] = [
        //                   ['callMe', [1, 3] ],
        //                   ['callMe', [3, 4] ],
        //                  ];

        // $this->rlist->newRule('Y', $rule1);

        // $rule2 = [];
        // $rule2['instanceOf'] = 'Y';
        // $rule2['inherit'] = false;
        // $rule2['constructParams'] = ['Foo'];

        // $this->rlist->newRule('$MyInstance', $rule2);

        // $this->assertEquals($rule2, $dice->getRule('$MyInstance'));
    }
}
